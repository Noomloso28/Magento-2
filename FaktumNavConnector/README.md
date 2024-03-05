# FAKTUM NAV Connector

## 1. Import cron
- Runs and executes the import processor

# 2. Import processor
- Loads the import data using ImportDataLoaderInterface
- Uses the correct importer for the entity

# 3. Importer
- Applies field mapping using the FieldMapper
- Parses field values
- Imports the data into Magento
