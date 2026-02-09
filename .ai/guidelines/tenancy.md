# General instructions

- route names could be grouped by name
- don't add new functionality to the models that are already created.
- use `Illuminate\Auth\Access\Response;` for the response instead or just `true` or `false` inside policies.
- use `@property-read` for the model attributes in the PHPDocBlock.
- When the new feature or change is related to the tenants and there is a new migration for the tenants, use `php artisan tenants:migrate` to run the migration for the tenants.
- Every time you finish adding new translations, make sure to run `composer lang:generate` to sync the translations.

# testing

- Use Pest for testing.
- Do your best to not use mocks.
- inside the `tests/Pest.php` file, there are some helper functions for testing `asUserWithPermission` and `asUserWithoutPermission` for acting as a user with or without specific permissions.
  - the permissions are defined in the `app/Enums/TenantPermission.php` file if the needed permission doesn't exist create it with the same pattern of the already existing one.
  - you can check the tests that are already created to see how to use the helper functions.
  - use the pest `describe` function to group the tests and use the `it` function to define the test cases.
    - use the `describe` function like this:
      ```php
      describe('if user has permission', function () {
          // test cases here
      });
      ```
- tenant-specific functionality should be tested within the `tests/Tenant` folder.
  - for example, if a controller is used in `routes/tenant.php` or any required inside it, its tests should be in `tests/Tenant/Feature/HTTP/{ResourceName}/{MethodName}Test.php`.
  - only add the tests for the methods that are used in the controller. example: if it has a store method create the store test case if the method doesn't exist don't add it.
    - for store use `CreateTest` as the file name.
    - for update use `UpdateTest` as the file name.
    - for delete use `DeleteTest` as the file name.
    - for index use `IndexTest` as the file name.
    - for restore use `RestoreTest` as the file name.
    - for force delete use `ForceDeleteTest` as the file name.
- add policies for the models that require them.
- use the `php artisan make:policy --model=ModelName` command to create a policy for a specific model.
  - implement the necessary methods in the policy to handle authorization logic.
- add `Gate::authorize` in the controller methods to check for permissions.
- run the tests and make sure they pass.
- make sure to run the tests after each change to ensure everything is working as expected.
- use `./vendor/bin/pest {folderPath}` to run the tests.
  - for example, to run the tests for the OfferingType resource, you can use `./vendor/bin/pest tests/Tenant/Feature/HTTP/OfferingType`.
