# Code Refactoring (Digital Tolk)

I really liked the repository pattern to easily scale up the application. It has those amazing characteristics to help expand the app with very little effort.

Although I really like the architecture, the coding style is not my favorite. It is lacking many of the basic coding practices used by many developers. For example, it is very inconsistent in terms of coding-case, request inputs, response and function naming etc. It also lacks readability due to multiple reasons, like over-nesting code, improper names, commented code and improper code grouping etc.

I have not modified the logic or implementation of the application for a number of reasons, but I have refactored the code as much as I could with the following terms in mind:

1. Clean code and Readability
2. Consistency
3. Reusability
4. Efficiency

## 1. Clean code and Readability
- The code-nesting should be minimal 
- There should be proper spacing between code lines and words
- Its better to visually group the related code 
- PSR is highly recommended 
- Naming conventions must be proper and consistent 
- There should be no comment code
- Order of occurrence of variable should also be consistent
- Ternary operators should be used for binary conditions 
- Ideally array should contain a trailing comma
- We can have comments on top of methods to help other developers

## 2. Consistency
- Request parameters should not be inconsistent in terms of case and name etc.
- Response should also be consistent (like somewhere the response is simple string, while somewhere `response()` object etc.)
- Helpers should follow the same pattern (like either `config()` or `env()`)
- Request name should match with variables
- Overall, the code style should be consistent (like somewhere `if` statement is used with `{}` and in the next line `{}` are omitted)

## 3. Reusability
- There are several places which can be optimized in a better way to re-use most of the code, specially in `BookingRepository`.

## 4. Efficiency
- There should be no extra variables (like data or response variables, so that even without these the application works perfectly fine)
- We can use negative conditions such that we can reduce the unnecessary code-nesting.
- There are many expensive queries too. For which, I would need more insight into the models to properly optimize those.
- Instead of `curl()` we can use `GuzzleHttp` client for better server-to-server communication

# Test Cases

I have added two files for the tests:

1. TeHelperTest.php
2. UserRepositoryTest.php

## 1. TeHelperTest.php
This test file contains one test-case to assert the working of `willExpireAt()` helper.

## 2. UserRepositoryTest.php
This test file contains two test-case to assert the working of `createOrUpdate()` method.

### `it_can_create_a_new_user()`
This test-case will be used to test the working of a new user.

### `it_can_update_an_existing_user()`
This test-case will be used to test the working of an existing user.

----

Due to limited time, I could not refactor each and every bit of the code, although I really wanted to. Anyways, I enjoyed the task very much and, hopefully, it should be sufficient for now.

Thank you for this opportunity.

Best, <br />
Taimoor Ali <br />
Web Developer <br />
