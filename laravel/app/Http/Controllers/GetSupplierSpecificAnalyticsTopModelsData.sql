CREATE PROCEDURE [dbo].[GetSupplierSpecificAnalyticsTopModelsData]
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
		
		-- Get sources into temp table
		DECLARE @Tbl_Source AS TABLE (Source VARCHAR(100))
		INSERT INTO @Tbl_Source
			SELECT * FROM dbo.fnParseArray(@LocalSource, @Split) 

        IF @SupplierType = @SUPPLIER_PREMIUM_TYPE_NAME OR @IsBackdoorEntryLogin=1
            BEGIN
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
		
			END           

        SELECT * FROM @AnalyticsData
	END
GO