# Laravel DDD API

A Laravel-based API project built with Domain-Driven Design and Clean Architecture principles.  
Designed for modularity, testability, and long-term maintainability.

## Architecture

This project follows a layered Clean Architecture:

- **Presentation Layer**: Controllers, ViewModels
- **Application Layer**: UseCases, Commands, DTOs, QueryServiceInterfaces
- **Domain Layer**: Entities, Value Objects, RepositoryInterfaces
- **Infrastructure Layer**: Eloquent Repositories, QueryServices

### API Flow

1. Controller constructs a Command object from request input.
2. The Command is passed to a UseCase.
3. UseCase invokes domain logic and persists data via a Repository.
4. Resulting Entity is mapped to a DTO.
5. The DTO is passed to a ViewModel and returned as a JsonResponse.

## Tech Stack

- PHP 8.x / Laravel 10.x
- MySQL or PostgreSQL
- PHPUnit (Unit/Feature testing)
- Laravel Scout + Elasticsearch (optional)

## Directory Structure
```
app/
└── Posts/
    ├── Application/
    │   ├── ApplicationTests/
    │   ├── UseCases/
    │   ├── UseCommands/
    │   ├── Dtos/
    │   └── QueryServiceInterface/
    ├── Domain/
    │   ├── DomainTests/
    │   ├── Entities/
    │   ├── ValueObjects/
    │   └── RepositoryInterfaces/
    ├── Infrastructure/
    │   ├── InfrastructureTests/
    │   ├── Repositories/
    │   └── QueryServices/
    └── Presentation/
        ├── Controllers/
        ├── PresentationTests/
        └── ViewModels/

└── Users/
    ├── Application/
    │   ├── ApplicationTests/
    │   ├── UseCases/
    │   ├── UseCommands/
    │   ├── Dtos/
    │   └── QueryServiceInterface/
    ├── Domain/
    │   ├── DomainTests/
    │   ├── Entities/
    │   ├── ValueObjects/
    │   └── RepositoryInterfaces/
    ├── Infrastructure/
    │   ├── InfrastructureTests/
    │   ├── Repositories/
    │   └── QueryServices/
    └── Presentation/
        ├── Controllers/
        ├── PresentationTests/
        └── ViewModels/
```
