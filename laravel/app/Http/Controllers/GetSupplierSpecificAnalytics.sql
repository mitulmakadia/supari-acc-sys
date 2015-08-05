CREATE PROCEDURE [dbo].[GetSupplierSpecificAnalyticsData]
    (
      @StartDate DATETIME ,
      @EndDate DATETIME ,
      @PreviousPeriodStartDate DATETIME ,
      @PreviousPeriodEndDate DATETIME ,
      @StateCode VARCHAR(20) ,
      @Source VARCHAR(20) ,
      @SupplierId INT,
      @IsBackdoorEntryLogin BIT
    )
AS 
    BEGIN
    
		DECLARE @LocalSupplierId INT = @SupplierId,
		@LocalStartDate DATETIME = @StartDate,
		@LocalEndDate DATETIME = @EndDate,
		@LocalPreviousPeriodStartDate DATETIME = @PreviousPeriodStartDate,
		@LocalPreviousPeriodEndDate DATETIME = @PreviousPeriodEndDate,
		@LocalStateCode VARCHAR(20) = @StateCode,
		@LocalSource VARCHAR(20) = @Source

	    DECLARE @supplierIds varchar(1000)

		SELECT @supplierIds = coalesce(@supplierIds + ',', '') + a.Id
		FROM (SELECT Cast(Id AS varchar(5)) AS Id from SupplierMaster where Id = @LocalSupplierId OR ParentId = @LocalSupplierId) a

        --DECLARE @ZipCodeTable AS TABLE ( ZipCode VARCHAR(10) )
        DECLARE @Split CHAR(1) = ','
        DECLARE @SUPPLIER_PREMIUM_TYPE_NAME VARCHAR(20) = 'Premium'
        DECLARE @SupplierType VARCHAR(20)
        DECLARE @AnalyticsData TABLE
            (
              Value VARCHAR(200) ,
              Type VARCHAR(50)
            )
            
        SELECT  @SupplierType = ISNULL(dbo.SupplierTypeMaster.SupplierType, '')
        FROM    dbo.SupplierMaster
                INNER JOIN dbo.SupplierTypeMaster ON dbo.SupplierMaster.Type = dbo.SupplierTypeMaster.Id
        WHERE   dbo.SupplierMaster.Id = @LocalSupplierId
		
		
        -- Retrieve ZipCode from StateCode
        --INSERT  INTO @ZipCodeTable
        --        ( ZipCode 
                        
        --        )
        --        SELECT DISTINCT
        --                ZipCode
        --        FROM    dbo.ZipCodes
        --                INNER JOIN dbo.StateMaster ON dbo.ZipCodes.StateId = dbo.StateMaster.Id
        --        WHERE   StateCode = @LocalStateCode
                        
		
		-- Get sources into temp table
		DECLARE @Tbl_Source AS TABLE (Source VARCHAR(100))
		INSERT INTO @Tbl_Source
			SELECT * FROM dbo.fnParseArray(@LocalSource, @Split) 

        IF @SupplierType = @SUPPLIER_PREMIUM_TYPE_NAME OR @IsBackdoorEntryLogin=1
            BEGIN
            
		--Current Period Supplier Impression    
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  ( dbo.GetSupplierImpression(@LocalStartDate, @LocalEndDate,
                                                    @LocalStateCode, @LocalSource,
                                                    @supplierIds) ,
                          'CurrentPeriodSupplierImpression'  
                        )
                
		------------------------------------------------------------------------------------------------------------------                            
                
        --Previous Period Supplier Impression    
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  ( dbo.GetSupplierImpression(@LocalPreviousPeriodStartDate,
                                                    @LocalPreviousPeriodEndDate,
                                                    @LocalStateCode, @LocalSource,
                                                    @supplierIds) ,
                          'PreviousPeriodSupplierImpression'  
                        )
            
		------------------------------------------------------------------------------------------------------------------            
        
        --Current Period Supplier Contact Request Count
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  ( dbo.GetSupplierContactRequestCount(@LocalStartDate,
                                                             @LocalEndDate,
                                                             @LocalStateCode,
                                                             @LocalSource,
                                                             @supplierIds) ,
                          'CurrentPeriodContactRequestCount'  
                        )   
                
		------------------------------------------------------------------------------------------------------------------                            
                
        --Previous Period Supplier Contact Request Count
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  ( dbo.GetSupplierContactRequestCount(@LocalPreviousPeriodStartDate,
                                                             @LocalPreviousPeriodEndDate,
                                                             @LocalStateCode,
                                                             @LocalSource,
                                                             @supplierIds) ,
                          'PreviousPeriodContactRequestCount'  
                        )      
                
		------------------------------------------------------------------------------------------------------------------                            
        
        --Current Period Supplier Ebay Click Count                  
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  ( dbo.GetSupplierEbayClickCount(@LocalStartDate, @LocalEndDate,
                                                        @LocalStateCode, @LocalSource,
                                                        @supplierIds) ,
                          'CurrentPeriodSupplierEbayClickCount'  
                        )    
                
		------------------------------------------------------------------------------------------------------------------                            
                
        --Previous Period Supplier Ebay Click Count                  
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  ( dbo.GetSupplierEbayClickCount(@LocalPreviousPeriodStartDate,
                                                        @LocalPreviousPeriodEndDate,
                                                        @LocalStateCode, @LocalSource,
                                                        @supplierIds) ,
                          'PreviousPeriodSupplierEbayClickCount'  
                        )
                
		------------------------------------------------------------------------------------------------------------------                            
                
        --Supplier Top Parts Searched
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                        SELECT TOP 10
                                dbo.PartMaster.PartName ,
                                'SupplierTopParts'
                        FROM    dbo.SupplierImpressionTracking
                                INNER JOIN dbo.SupplierAnalyticsView ON dbo.SupplierAnalyticsView.id = dbo.SupplierImpressionTracking.SearchRequest
                                INNER JOIN dbo.PartMaster ON dbo.SupplierAnalyticsView.PartId = dbo.PartMaster.Id
                        WHERE   dbo.SupplierImpressionTracking.Supplier IN (SELECT * FROM dbo.fnParseArray(@supplierIds, @Split))
                                AND CreatedDate >= @LocalStartDate
                                AND CreatedDate <= @LocalEndDate
                                AND ( @LocalSource = 'All'
                                      OR Source IN (SELECT Source FROM @Tbl_Source)
                                    )
                                AND ( @LocalStateCode = ''
                                      OR ( @LocalStateCode = 'Unknown'
                                           AND ( dbo.SupplierAnalyticsView.ZipCode IS NULL
                                                 OR dbo.SupplierAnalyticsView.ZipCode = ''
                                               )
                                         )
                                      --OR ZipCode IN ( SELECT  ZipCode
                                      --                FROM    @ZipCodeTable )
									  OR ZipCode IN (
														SELECT DISTINCT
																ZipCode
														FROM    dbo.ZipCodes
																INNER JOIN dbo.StateMaster ON dbo.ZipCodes.StateId = dbo.StateMaster.Id
														WHERE   StateCode = @LocalStateCode
													)
                                    )
                                AND SupplierAnalyticsView.PartId IS NOT NULL
                        GROUP BY dbo.PartMaster.PartName
                        ORDER BY COUNT(dbo.PartMaster.PartName) DESC ,
                                MAX(dbo.SupplierAnalyticsView.CreatedDate) DESC
                        
		------------------------------------------------------------------------------------------------------------------                                    

		--Top Supplier Ebay Listings
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                        SELECT TOP 10
                                ( CAST(dbo.SupplierAnalyticsView.Year AS VARCHAR(4))
                                  + ' ' + dbo.SupplierAnalyticsView.Make + ' '
                                  + dbo.SupplierAnalyticsView.Model + ' '
                                  + dbo.PartMaster.PartName ) ,
                                'SupplierTopEbayListings'
                        FROM    dbo.EbayTracking
                                INNER JOIN dbo.SupplierAnalyticsView ON dbo.EbayTracking.SearchRequestId = dbo.SupplierAnalyticsView.Id
                                INNER JOIN dbo.PartMaster ON dbo.SupplierAnalyticsView.PartId = dbo.PartMaster.Id
                        WHERE   dbo.EbayTracking.SupplierId IN (SELECT * FROM dbo.fnParseArray(@supplierIds, @Split))
                                AND dbo.SupplierAnalyticsView.CreatedDate >= @LocalStartDate
                                AND dbo.SupplierAnalyticsView.CreatedDate <= @LocalEndDate
                                AND ( @LocalSource = 'All'
                                      OR Source IN (SELECT Source FROM @Tbl_Source)
                                    )
                                AND ( @LocalStateCode = ''
                                      OR ( @LocalStateCode = 'Unknown'
                                           AND ( dbo.SupplierAnalyticsView.ZipCode IS NULL
                                                 OR dbo.SupplierAnalyticsView.ZipCode = ''
                                               )
                                         )
                                      --OR ZipCode IN ( SELECT  ZipCode
                                      --                FROM    @ZipCodeTable )
									  OR ZipCode IN (
														SELECT DISTINCT
																ZipCode
														FROM    dbo.ZipCodes
																INNER JOIN dbo.StateMaster ON dbo.ZipCodes.StateId = dbo.StateMaster.Id
														WHERE   StateCode = @LocalStateCode
													)
                                    )
                        GROUP BY dbo.SupplierAnalyticsView.Year ,
                                dbo.SupplierAnalyticsView.Make ,
                                dbo.SupplierAnalyticsView.Model ,
                                dbo.PartMaster.PartName
                        ORDER BY COUNT(*) DESC ,
                                MAX(dbo.SupplierAnalyticsView.CreatedDate) DESC                             
                      
		------------------------------------------------------------------------------------------------------------------
		
		        --Supplier Top Models Searched
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        
                        )
                        SELECT TOP 10
                                dbo.SupplierAnalyticsView.Make + ' '
                                + dbo.SupplierAnalyticsView.Model ,
                                'SupplierTopModels'
                        FROM    dbo.SupplierImpressionTracking
                                INNER JOIN dbo.SupplierAnalyticsView ON dbo.SupplierAnalyticsView.id = dbo.SupplierImpressionTracking.SearchRequest
                        WHERE   dbo.SupplierImpressionTracking.Supplier IN (SELECT * FROM dbo.fnParseArray(@supplierIds, @Split))
                                AND CreatedDate >= @LocalStartDate
                                AND CreatedDate <= @LocalEndDate
                                AND ( @LocalSource = 'All'
                                      OR Source IN (SELECT Source FROM @Tbl_Source)
                                    )
                                AND ( @LocalStateCode = ''
                                      OR ( @LocalStateCode = 'Unknown'
                                           AND ( dbo.SupplierAnalyticsView.ZipCode IS NULL
                                                 OR dbo.SupplierAnalyticsView.ZipCode = ''
                                               )
                                         )
                                      --OR ZipCode IN ( SELECT  ZipCode
                                      --                FROM    @ZipCodeTable )
									  OR ZipCode IN (
														SELECT DISTINCT
																ZipCode
														FROM    dbo.ZipCodes
																INNER JOIN dbo.StateMaster ON dbo.ZipCodes.StateId = dbo.StateMaster.Id
														WHERE   StateCode = @LocalStateCode
													)
                                    )
								AND SupplierAnalyticsView.Model IS NOT NULL
                        GROUP BY dbo.SupplierAnalyticsView.Make ,
                                dbo.SupplierAnalyticsView.Model
                        ORDER BY COUNT(*) DESC ,
                                MAX(dbo.SupplierAnalyticsView.CreatedDate) DESC  
                        
		------------------------------------------------------------------------------------------------------------------ 
        
        --Current period supplier's parts inventoried
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  (dbo.GetSupplierPartsInventoried(@LocalStartDate, @LocalEndDate,@supplierIds) ,
                          'CurrentPeriodSupplierPartsInventoried'  
                        )    
                
		------------------------------------------------------------------------------------------------------------------                            
                
        --Previous Period Supplier's parts inventoried
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  (dbo.GetSupplierPartsInventoried(@LocalPreviousPeriodStartDate,@LocalPreviousPeriodEndDate,@supplierIds) ,
                          'PreviousPeriodSupplierPartsInventoried'  
                        )
                
		------------------------------------------------------------------------------------------------------------------                            		
		
        --Current period supplier's cars inventoried
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  (dbo.GetSupplierCarsInventoried(@LocalStartDate, @LocalEndDate,@supplierIds) ,
                          'CurrentPeriodSupplierCarsInventoried'  
                        )    
                
		------------------------------------------------------------------------------------------------------------------                            
                
        --Previous Period Supplier's cars inventoried
                INSERT  INTO @AnalyticsData
                        ( Value ,
                          Type
                        )
                VALUES  (dbo.GetSupplierCarsInventoried(@LocalPreviousPeriodStartDate,@LocalPreviousPeriodEndDate,@supplierIds) ,
                          'PreviousPeriodSupplierCarsInventoried'  
                        )
                
		------------------------------------------------------------------------------------------------------------------                            				
		
            END           

        SELECT * FROM @AnalyticsData
END
GO