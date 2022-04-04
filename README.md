# Leadobo Integration Template [WIP]
Start your Integration with this Template...

## Integration Structure
Columns: Name, Github, Group, Folder, Multiple

### Files
- Composer.json *Must Also Append To Root Composer.json*
- /assets/ *Static Assets Like Images*
- /actions/
  - addScript.php
  - sendData.php
  - /jobs/ *Any Async Actions Go Here*
- /database/
  - /seeders/ *⚠ Check Data First! Shouldn’t Use IDs For Relationships?*
    - RequirementSeeder.php
    - SettingSeeder.php
    - FieldMapSeeder.php
  - /migrations/ *Maybe Not Necessary*

**ⓘ Actions Are Automatically Seeded From Action Files**

Example: Stripe Integration requires StripePHP Library Similar to Laravel Nova Custom Fields, Each Integration has its own composer.json
