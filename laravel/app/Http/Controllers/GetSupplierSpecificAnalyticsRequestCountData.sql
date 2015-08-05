CREATE PROCEDURE [dbo].[GetSupplierSpecificAnalyticsRequestCountData]
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
			END           

        SELECT * FROM @AnalyticsData
	END
GO