# Migrate

Based on https://github.com/doctrine/jira-github-issues/

## Prerequisites

### Composer

### Create .env file

### Configurations

Every configuration is a simple key/value registry.

#### Users

You must create the `configs/users.php` file, the file `configs/users.php.dist` exists to be used as a template.

Example: 
```php
<?php
return [
    'vinicius_on_jira' => 'vinicius_on_github',
    'another_on_jira' => 'another_on_github',
    'user_on_jira_doenst_exists_on_github' => '',
];
```

Every Jira user must be placed on file.

#### Status

@todo Finalize the readme


## Run the migration

1. Invite your users to github repository. Run: `php invite-users.php`;
2. Retrieve your projects from Jira. Run: `php get-projects.php`;
3. Retrieve your issues from Jira. Run: `php get-issues.php`;
4. Create every project as milestone. Run `php create-milestones.php`;
5. Map the Jira issues based on configs. Run `php map-issues.php`;
6. Import the issues on Github. Run `php create-issues.php`;
7. Consult the status of importation. Run `php consult-imports.`;
8. Import the Jira subtasks on Github. Run `php create-substasks.php`;

**You must repeat the steaps 5,6,7,8**

### Know issues

