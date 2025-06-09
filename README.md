# 🏗️ Aplikacja TODO w Symfony - Architektura Hexagonalna

Kompletna implementacja aplikacji TODO z wykorzystaniem **architektury hexagonalnej**, **Domain-Driven Design (DDD)**, **CQRS** i **nowoczesnych wzorców projektowych** w Symfony.

## 📋 Spis treści

- [Opis projektu](#opis-projektu)
- [Architektura](#architektura)
- [Struktura plików](#struktura-plików)
- [Instalacja](#instalacja)
- [API Endpoints](#api-endpoints)
- [Wzorce projektowe](#wzorce-projektowe)
- [Testowanie](#testowanie)
- [Troubleshooting](#troubleshooting)
- [Rozszerzenia](#rozszerzenia)

## 🎯 Opis projektu

Aplikacja TODO zbudowana zgodnie z zasadami **Clean Architecture** i **Domain-Driven Design**. Projekt demonstruje:

- **Separację logiki biznesowej** od szczegółów technicznych
- **Testowalność** przez dependency injection i interfejsy
- **Maintainability** dzięki jasno zdefiniowanym warstwom
- **Flexibility** - łatwą wymianę implementacji

### ✨ Kluczowe cechy

- ✅ **Hexagonal Architecture** (Ports & Adapters)
- ✅ **CQRS** - rozdzielenie komend i zapytań
- ✅ **DDD** - bogaty model domenowy
- ✅ **Command/Query Bus** z Symfony Messenger
- ✅ **Repository Pattern** z Doctrine ORM
- ✅ **Value Objects** (UUID)
- ✅ **DTO Pattern** dla transferu danych
- ✅ **REST API** z pełnym CRUD

## 🏛️ Architektura

```
┌─────────────────────────────────────────────────────────┐
│                  INFRASTRUCTURE                         │
│  ┌─────────────┐  ┌──────────────┐  ┌─────────────────┐ │
│  │ Controller  │  │ Repository   │  │ Entity (ORM)    │ │
│  │             │  │ (Doctrine)   │  │                 │ │
│  └─────────────┘  └──────────────┘  └─────────────────┘ │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────┐
│                APPLICATION                              │
│  ┌─────────────┐  ┌┴──────────────┐  ┌─────────────────┐ │
│  │   UseCase   │  │     DTO       │  │   Command/Query │ │
│  │  Handlers   │  │              │  │      Bus        │ │
│  └─────────────┘  └───────────────┘  └─────────────────┘ │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────┐
│                  DOMAIN                                 │
│  ┌─────────────┐  ┌┴──────────────┐  ┌─────────────────┐ │
│  │   Entity    │  │   Service     │  │   Repository    │ │
│  │   (Todo)    │  │               │  │   Interface     │ │
│  └─────────────┘  └───────────────┘  └─────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

### 📦 Warstwy aplikacji

1. **Domain** - Czysta logika biznesowa, niezależna od frameworka
2. **Application** - Use cases, orchestracja procesów biznesowych
3. **Infrastructure** - Implementacje techniczne, adaptery zewnętrzne

## 📁 Struktura plików

```
src/
├── TodoList/                           # Bounded Context
│   ├── Domain/                         # 🎯 DOMENA - Logika biznesowa
│   │   ├── Entity/
│   │   │   └── Todo.php                # Encja domenowa z logiką
│   │   ├── Repository/
│   │   │   └── TodoRepositoryInterface.php  # Port (interfejs)
│   │   ├── Service/
│   │   │   ├── TodoServiceInterface.php     # Interfejs serwisu
│   │   │   └── TodoService.php         # Serwis domenowy
│   │   └── Exception/
│   │       └── TodoNotFoundException.php    # Wyjątek domenowy
│   │
│   ├── Application/                    # 🚀 APLIKACJA - Use Cases
│   │   ├── UseCase/                    # CQRS Commands & Queries
│   │   │   ├── CreateTodo/
│   │   │   │   ├── CreateTodoCommand.php    # Komenda
│   │   │   │   └── CreateTodoHandler.php    # Handler z __invoke()
│   │   │   ├── GetTodo/
│   │   │   │   ├── GetTodoQuery.php         # Zapytanie
│   │   │   │   └── GetTodoHandler.php       # Handler z __invoke()
│   │   │   ├── GetAllTodos/
│   │   │   │   ├── GetAllTodosQuery.php
│   │   │   │   └── GetAllTodosHandler.php
│   │   │   ├── UpdateTodo/
│   │   │   │   ├── UpdateTodoCommand.php
│   │   │   │   └── UpdateTodoHandler.php
│   │   │   ├── CompleteTodo/
│   │   │   │   ├── CompleteTodoCommand.php
│   │   │   │   └── CompleteTodoHandler.php
│   │   │   └── DeleteTodo/
│   │   │       ├── DeleteTodoCommand.php
│   │   │       └── DeleteTodoHandler.php
│   │   └── DTO/
│   │       └── TodoDTO.php             # Data Transfer Object
│   │
│   └── Infrastructure/                 # 🔧 INFRASTRUKTURA - Adaptery
│       ├── Repository/
│       │   └── DoctrineTodoRepository.php   # Adapter (implementacja)
│       ├── Controller/
│       │   └── TodoController.php      # REST API Controller
│       └── Persistence/
│           └── Entity/
│               └── TodoEntity.php      # Encja ORM (mapping)
│
└── Shared/                             # 🤝 WSPÓŁDZIELONE
    ├── Domain/
    │   └── ValueObject/
    │       └── Uuid.php                # Value Object
    └── Infrastructure/
        └── Bus/
            ├── CommandBusInterface.php # Port dla komend
            ├── QueryBusInterface.php   # Port dla zapytań
            ├── SymfonyCommandBus.php   # Adapter Symfony Messenger
            └── SymfonyQueryBus.php     # Adapter Symfony Messenger

config/
├── services.yaml                       # Konfiguracja DI
└── packages/
    └── messenger.yaml                  # Konfiguracja Command/Query Bus
```

## 🔑 Najważniejsze elementy

### 🎯 Domain Layer

#### `Todo.php` - Encja domenowa
```php
final class Todo
{
    // ✅ Rich Domain Model - logika w encji
    public function markAsCompleted(): void
    public function updateTitle(string $title): void
    // ✅ Enkapsulacja stanu i invariantów
}
```

#### `TodoRepositoryInterface.php` - Port
```php
interface TodoRepositoryInterface
{
    // ✅ Abstrakcja dostępu do danych
    public function save(Todo $todo): void;
    public function findById(Uuid $id): ?Todo;
}
```

#### `TodoServiceInterface.php` - Interfejs serwisu
```php
interface TodoServiceInterface
{
    // ✅ Testowalność - możliwość mockowania
    public function createTodo(string $title, string $description): Todo;
    public function completeTodo(Uuid $id): Todo;
}
```

#### `TodoService.php` - Serwis domenowy
```php
final class TodoService implements TodoServiceInterface
{
    // ✅ Orchestracja operacji domenowych
    // ✅ Niezależny od infrastruktury
    public function createTodo(string $title, string $description): Todo
}
```

### 🚀 Application Layer

#### Use Case Handlers
```php
final class CreateTodoHandler
{
    // ✅ __invoke() dla Symfony Messenger
    public function __invoke(CreateTodoCommand $command): TodoDTO
    
    // ✅ Single Responsibility - jedna operacja
    // ✅ Dependency Injection
}
```

#### `TodoDTO.php` - Data Transfer Object
```php
final readonly class TodoDTO
{
    // ✅ Immutable DTO
    // ✅ Separacja modelu domenowego od API
    public static function fromDomain(Todo $todo): self
}
```

### 🔧 Infrastructure Layer

#### `DoctrineTodoRepository.php` - Adapter
```php
final class DoctrineTodoRepository implements TodoRepositoryInterface
{
    public function save(Todo $todo): void
    {
        $existingEntity = $this->entityManager
            ->getRepository(TodoEntity::class)
            ->find($todo->getId()->value());

        if ($existingEntity) {
            // UPDATE - aktualizuj istniejącą encję
            $existingEntity->updateFromDomain($todo);
        } else {
            // CREATE - dodaj nową encję
            $todoEntity = TodoEntity::fromDomain($todo);
            $this->entityManager->persist($todoEntity);
        }
        
        $this->entityManager->flush();
    }
}
```

#### `TodoController.php` - REST API
```php
final class TodoController extends AbstractController
{
    // ✅ Wykorzystuje Command/Query Bus z __invoke()
    public function createTodo(Request $request): JsonResponse
    {
        $command = new CreateTodoCommand($data['title'], $data['description']);
        $todoDTO = ($this->commandBus)($command);
        return $this->json($todoDTO, 201);
    }
}
```

#### `TodoEntity.php` - ORM Mapping
```php
#[ORM\Entity]
#[ORM\Table(name: 'todos')]
class TodoEntity
{
    // ✅ Czysta encja persystencji
    // ✅ Mapowanie Domain ↔ ORM
    public static function fromDomain(Todo $todo): self
    public function toDomain(): Todo
    public function updateFromDomain(Todo $todo): void  // Dla UPDATE
}
```

### 🤝 Shared Layer

#### `Uuid.php` - Value Object
```php
final readonly class Uuid
{
    // ✅ Immutable Value Object
    // ✅ Walidacja w konstruktorze
    // ✅ Equality przez wartość
    public function equals(Uuid $other): bool
}
```

#### Command/Query Bus
```php
interface CommandBusInterface
{
    public function __invoke(object $command): mixed;
}

// ✅ Centralne przetwarzanie komend/zapytań
// ✅ Middleware: walidacja, transakcje, logowanie
```

## 🚀 Instalacja

### Wymagania
- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Symfony CLI (opcjonalne)

### Kroki instalacji

```bash
# 1. Klonowanie repozytorium
git clone <repository-url>
cd symfony-hexagonal-todo

# 2. Instalacja zależności
composer install

# 3. Konfiguracja środowiska
cp .env .env.local
# Edytuj DATABASE_URL w .env.local

# 4. Utworzenie bazy danych
php bin/console doctrine:database:create

# 5. Migracje
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# 6. Czyszczenie cache
php bin/console cache:clear

# 7. Uruchomienie serwera
symfony server:start
# lub
php -S localhost:8000 -t public/
```

### Wymagane pakiety Composer
```bash
composer require symfony/framework-bundle
composer require symfony/messenger
composer require doctrine/orm
composer require doctrine/doctrine-bundle
composer require ramsey/uuid
```

## 🔗 API Endpoints

### Base URL: `http://localhost:8000/api/todos`

| Metoda | Endpoint | Opis | Body |
|--------|----------|------|------|
| `GET` | `/` | Lista wszystkich TODO | - |
| `GET` | `/{id}` | Szczegóły TODO | - |
| `POST` | `/` | Tworzenie TODO | `{"title": "...", "description": "..."}` |
| `PUT` | `/{id}` | Aktualizacja TODO | `{"title": "...", "description": "..."}` |
| `PATCH` | `/{id}/complete` | Oznacz jako ukończone | - |
| `DELETE` | `/{id}` | Usunięcie TODO | - |

### Przykłady użycia

```bash
# Tworzenie TODO
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title": "Nauka DDD", "description": "Implementacja wzorców"}'

# Pobieranie wszystkich
curl http://localhost:8000/api/todos

# Ukończenie zadania
curl -X PATCH http://localhost:8000/api/todos/123e4567-e89b-12d3-a456-426614174000/complete
```

### Przykładowa odpowiedź JSON
```json
{
  "id": "123e4567-e89b-12d3-a456-426614174000",
  "title": "Nauka DDD",
  "description": "Implementacja wzorców projektowych",
  "completed": false,
  "createdAt": "2024-12-08 15:30:00",
  "completedAt": null
}
```

## 🏗️ Wzorce projektowe

### Architektoniczne
- **🏛️ Hexagonal Architecture** - oddzielenie logiki od technologii
- **🏗️ Clean Architecture** - dependency rule, warstwy
- **🎯 Domain-Driven Design** - bogaty model domenowy
- **⚡ CQRS** - rozdzielenie komend i zapytań

### Projektowe
- **🗄️ Repository Pattern** - abstrakcja dostępu do danych
- **📦 DTO Pattern** - transfer danych między warstwami
- **🎭 Command Pattern** - enkapsulacja operacji
- **🏭 Factory Pattern** - tworzenie obiektów (DTO::fromDomain)
- **💎 Value Object Pattern** - immutable objekty wartości
- **🔌 Adapter Pattern** - implementacje interfejsów
- **🚌 Mediator Pattern** - Command/Query Bus
- **💉 Dependency Injection** - inwersja kontroli

### Symfony-specific
- **📨 Messenger Pattern** - asynchroniczne przetwarzanie
- **🏷️ Service Container** - zarządzanie zależnościami
- **🎯 Attribute-based Configuration** - adnotacje PHP 8

## 🧪 Testowanie

### Struktura testów
```
tests/
├── Unit/
│   ├── Domain/
│   │   ├── Entity/TodoTest.php
│   │   └── Service/TodoServiceTest.php
│   └── Application/
│       └── UseCase/CreateTodoHandlerTest.php
├── Integration/
│   └── Repository/DoctrineTodoRepositoryTest.php
└── Functional/
    └── Controller/TodoControllerTest.php
```

### Przykład testu jednostkowego
```php
final class CreateTodoHandlerTest extends TestCase
{
    public function test_should_create_todo_successfully(): void
    {
        // Given
        $todoService = $this->createMock(TodoServiceInterface::class); // ← Interfejs!
        $handler = new CreateTodoHandler($todoService);
        $command = new CreateTodoCommand('Test', 'Description');
        
        // When
        $result = $handler->__invoke($command); // ← __invoke zamiast handle
        
        // Then
        $this->assertEquals('Test', $result->title);
    }
}
```

### Uruchomienie testów
```bash
# Wszystkie testy
php bin/phpunit

# Tylko testy jednostkowe
php bin/phpunit tests/Unit

# Z coverage
php bin/phpunit --coverage-html coverage
```

## 🔧 Troubleshooting

### Popularne problemy i rozwiązania

#### **Problem: `Call to undefined method ::merge()`**
```
Handling failed: Call to undefined method EntityManager::merge()
```

**Rozwiązanie:** Doctrine 3.x usuwa `merge()`. Użyj pattern UPDATE/CREATE:
```php
public function save(Todo $todo): void
{
    $existingEntity = $this->entityManager
        ->getRepository(TodoEntity::class)
        ->find($todo->getId()->value());

    if ($existingEntity) {
        $existingEntity->updateFromDomain($todo); // UPDATE
    } else {
        $todoEntity = TodoEntity::fromDomain($todo);
        $this->entityManager->persist($todoEntity); // CREATE
    }
    
    $this->entityManager->flush();
}
```

#### **Problem: `Class is declared "final" and cannot be doubled`**
```
Class "TodoService" is declared "final" and cannot be doubled
```

**Rozwiązanie:** Używaj interfejsów w testach:
```php
// ❌ Złe - mockowanie final class
$this->createMock(TodoService::class)

// ✅ Dobre - mockowanie interfejsu
$this->createMock(TodoServiceInterface::class)
```

#### **Problem: `Invalid handler service: must have an "__invoke()" method`**
```
Invalid handler service: must have an "__invoke()" method
```

**Rozwiązanie:** Wszystkie Symfony Messenger handlery muszą mieć `__invoke()`:
```php
// ❌ Złe
public function handle(CreateTodoCommand $command): TodoDTO

// ✅ Dobre
public function __invoke(CreateTodoCommand $command): TodoDTO
```

#### **Problem: Doctrine Identity Map conflict**
```
Another object was already present for the same ID
```

**Rozwiązanie:** Sprawdzaj czy encja istnieje przed `persist()`:
```php
// ❌ Złe - zawsze persist
$this->entityManager->persist(TodoEntity::fromDomain($todo));

// ✅ Dobre - sprawdź czy istnieje
$existing = $this->repository->find($todo->getId());
if ($existing) {
    $existing->updateFromDomain($todo); // UPDATE
} else {
    $this->entityManager->persist($new); // CREATE
}
```

### Popularne komendy debugowania
```bash
# Sprawdzenie serwisów
php bin/console debug:container | grep Todo

# Sprawdzenie message handlers
php bin/console debug:messenger

# Sprawdzenie routingu
php bin/console debug:router

# Sprawdzenie autowiring
php bin/console debug:autowiring

# Czyszczenie cache
php bin/console cache:clear
composer dump-autoload
```

## 🚀 Rozszerzenia

### Możliwe rozszerzenia aplikacji

1. **🔐 Autoryzacja** - JWT, OAuth2
2. **👥 Multi-tenancy** - TODO per user
3. **🔔 Events** - Domain Events, Event Sourcing
4. **📊 Metrics** - Monitoring, Logging
5. **🗃️ Caching** - Redis, Memcached
6. **⚡ Async** - Queue workers, Symfony Messenger
7. **🧪 Advanced Testing** - Behat, Mutation Testing
8. **📚 API Documentation** - OpenAPI, Swagger
9. **🔍 Search** - Elasticsearch
10. **📱 GraphQL** - Alternative API

### Event Sourcing Extension
```php
// Przykład rozszerzenia o Event Sourcing
final class TodoCreatedEvent
{
    public function __construct(
        public readonly string $todoId,
        public readonly string $title,
        public readonly \DateTimeImmutable $occurredAt
    ) {}
}
```

### Specification Pattern Extension
```php
// Przykład rozszerzenia o Specification Pattern
final class CompletedTodoSpecification implements TodoSpecificationInterface
{
    public function isSatisfiedBy(Todo $todo): bool
    {
        return $todo->isCompleted();
    }
}
```

## 📚 Zasoby i dokumentacja

### Architektura
- [Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Domain-Driven Design](https://martinfowler.com/bliki/DomainDrivenDesign.html)

### Symfony
- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [Symfony Messenger](https://symfony.com/doc/current/messenger.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)

### Wzorce projektowe
- [Enterprise Integration Patterns](https://www.enterpriseintegrationpatterns.com/)
- [Martin Fowler's Patterns](https://martinfowler.com/eaaCatalog/)

---

## 👥 Autorzy i licencja

**Autor:** Implementacja wzorców architektonicznych w Symfony  
**Licencja:** MIT  
**Version:** 1.0.0

---

🎯 **Ten projekt demonstruje best practices nowoczesnego developmentu w PHP z wykorzystaniem Symfony i wzorców architektonicznych enterprise-level.** ✅ Dependency Injection
}
```

#### `TodoDTO.php` - Data Transfer Object
```php
final readonly class TodoDTO
{
    // ✅ Immutable DTO
    // ✅ Separacja modelu domenowego od API
    public static function fromDomain(Todo $todo): self
}
```

### 🔧 Infrastructure Layer

#### `DoctrineTodoRepository.php` - Adapter
```php
final class DoctrineTodoRepository implements TodoRepositoryInterface
{
    // ✅ Implementacja portu domenowego
    // ✅ Mapping między Domain Entity a ORM Entity
}
```

#### `TodoController.php` - REST API
```php
final class TodoController extends AbstractController
{
    // ✅ Wykorzystuje Command/Query Bus
    // ✅ Obsługa HTTP i serializacja JSON
    public function createTodo(Request $request): JsonResponse
    {
        $command = new CreateTodoCommand($data['title'], $data['description']);
        $todoDTO = ($this->commandBus)($command);
        return $this->json($todoDTO, 201);
    }
}
```

#### `TodoEntity.php` - ORM Mapping
```php
#[ORM\Entity]
#[ORM\Table(name: 'todos')]
class TodoEntity
{
    // ✅ Czysta encja persystencji
    // ✅ Mapowanie Domain ↔ ORM
    public static function fromDomain(Todo $todo): self
    public function toDomain(): Todo
}
```

### 🤝 Shared Layer

#### `Uuid.php` - Value Object
```php
final readonly class Uuid
{
    // ✅ Immutable Value Object
    // ✅ Walidacja w konstruktorze
    // ✅ Equality przez wartość
    public function equals(Uuid $other): bool
}
```

#### Command/Query Bus
```php
interface CommandBusInterface
{
    public function __invoke(object $command): mixed;
}

// ✅ Centralne przetwarzanie komend/zapytań
// ✅ Middleware: walidacja, transakcje, logowanie
```

## 🚀 Instalacja

### Wymagania
- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Symfony CLI (opcjonalne)

### Kroki instalacji

```bash
# 1. Klonowanie repozytorium
git clone <repository-url>
cd symfony-hexagonal-todo

# 2. Instalacja zależności
composer install

# 3. Konfiguracja środowiska
cp .env .env.local
# Edytuj DATABASE_URL w .env.local

# 4. Utworzenie bazy danych
php bin/console doctrine:database:create

# 5. Migracje
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# 6. Czyszczenie cache
php bin/console cache:clear

# 7. Uruchomienie serwera
symfony server:start
# lub
php -S localhost:8000 -t public/
```

### Wymagane pakiety Composer
```bash
composer require symfony/framework-bundle
composer require symfony/messenger
composer require doctrine/orm
composer require doctrine/doctrine-bundle
composer require ramsey/uuid
```

## 🔗 API Endpoints

### Base URL: `http://localhost:8000/api/todos`

| Metoda | Endpoint | Opis | Body |
|--------|----------|------|------|
| `GET` | `/` | Lista wszystkich TODO | - |
| `GET` | `/{id}` | Szczegóły TODO | - |
| `POST` | `/` | Tworzenie TODO | `{"title": "...", "description": "..."}` |
| `PUT` | `/{id}` | Aktualizacja TODO | `{"title": "...", "description": "..."}` |
| `PATCH` | `/{id}/complete` | Oznacz jako ukończone | - |
| `DELETE` | `/{id}` | Usunięcie TODO | - |

### Przykłady użycia

```bash
# Tworzenie TODO
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title": "Nauka DDD", "description": "Implementacja wzorców"}'

# Pobieranie wszystkich
curl http://localhost:8000/api/todos

# Ukończenie zadania
curl -X PATCH http://localhost:8000/api/todos/123e4567-e89b-12d3-a456-426614174000/complete
```

### Przykładowa odpowiedź JSON
```json
{
  "id": "123e4567-e89b-12d3-a456-426614174000",
  "title": "Nauka DDD",
  "description": "Implementacja wzorców projektowych",
  "completed": false,
  "createdAt": "2024-12-08 15:30:00",
  "completedAt": null
}
```

## 🏗️ Wzorce projektowe

### Architektoniczne
- **🏛️ Hexagonal Architecture** - oddzielenie logiki od technologii
- **🏗️ Clean Architecture** - dependency rule, warstwy
- **🎯 Domain-Driven Design** - bogaty model domenowy
- **⚡ CQRS** - rozdzielenie komend i zapytań

### Projektowe
- **🗄️ Repository Pattern** - abstrakcja dostępu do danych
- **📦 DTO Pattern** - transfer danych między warstwami
- **🎭 Command Pattern** - enkapsulacja operacji
- **🏭 Factory Pattern** - tworzenie obiektów (DTO::fromDomain)
- **💎 Value Object Pattern** - immutable objekty wartości
- **🔌 Adapter Pattern** - implementacje interfejsów
- **🚌 Mediator Pattern** - Command/Query Bus
- **💉 Dependency Injection** - inwersja kontroli

### Symfony-specific
- **📨 Messenger Pattern** - asynchroniczne przetwarzanie
- **🏷️ Service Container** - zarządzanie zależnościami
- **🎯 Attribute-based Configuration** - adnotacje PHP 8

## 🧪 Testowanie

### Struktura testów
```
tests/
├── Unit/
│   ├── Domain/
│   │   ├── Entity/TodoTest.php
│   │   └── Service/TodoServiceTest.php
│   └── Application/
│       └── UseCase/CreateTodoHandlerTest.php
├── Integration/
│   └── Repository/DoctrineTodoRepositoryTest.php
└── Functional/
    └── Controller/TodoControllerTest.php
```

### Przykład testu jednostkowego
```php
final class CreateTodoHandlerTest extends TestCase
{
    public function test_should_create_todo_successfully(): void
    {
        // Given
        $todoService = $this->createMock(TodoService::class);
        $handler = new CreateTodoHandler($todoService);
        $command = new CreateTodoCommand('Test', 'Description');
        
        // When
        $result = $handler->__invoke($command);
        
        // Then
        $this->assertEquals('Test', $result->title);
    }
}
```

### Uruchomienie testów
```bash
# Wszystkie testy
php bin/phpunit

# Tylko testy jednostkowe
php bin/phpunit tests/Unit

# Z coverage
php bin/phpunit --coverage-html coverage
```

## 🔧 Debugging i rozwiązywanie problemów

### Popularne komendy debugowania
```bash
# Sprawdzenie serwisów
php bin/console debug:container | grep Todo

# Sprawdzenie message handlers
php bin/console debug:messenger

# Sprawdzenie routingu
php bin/console debug:router

# Sprawdzenie autowiring
php bin/console debug:autowiring
```

### Czyszczenie cache
```bash
php bin/console cache:clear
composer dump-autoload
```

## 🚀 Rozszerzenia

### Możliwe rozszerzenia aplikacji

1. **🔐 Autoryzacja** - JWT, OAuth2
2. **👥 Multi-tenancy** - TODO per user
3. **🔔 Events** - Domain Events, Event Sourcing
4. **📊 Metrics** - Monitoring, Logging
5. **🗃️ Caching** - Redis, Memcached
6. **⚡ Async** - Queue workers, Symfony Messenger
7. **🧪 Advanced Testing** - Behat, Mutation Testing
8. **📚 API Documentation** - OpenAPI, Swagger
9. **🔍 Search** - Elasticsearch
10. **📱 GraphQL** - Alternative API

### Event Sourcing Extension
```php
// Przykład rozszerzenia o Event Sourcing
final class TodoCreatedEvent
{
    public function __construct(
        public readonly string $todoId,
        public readonly string $title,
        public readonly \DateTimeImmutable $occurredAt
    ) {}
}
```

### Specification Pattern Extension
```php
// Przykład rozszerzenia o Specification Pattern
final class CompletedTodoSpecification implements TodoSpecificationInterface
{
    public function isSatisfiedBy(Todo $todo): bool
    {
        return $todo->isCompleted();
    }
}
```

## 📚 Zasoby i dokumentacja

### Architektura
- [Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Domain-Driven Design](https://martinfowler.com/bliki/DomainDrivenDesign.html)

### Symfony
- [Symfony Documentation](https://symfony.com/doc/current/index.html)
- [Symfony Messenger](https://symfony.com/doc/current/messenger.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)

### Wzorce projektowe
- [Enterprise Integration Patterns](https://www.enterpriseintegrationpatterns.com/)
- [Martin Fowler's Patterns](https://martinfowler.com/eaaCatalog/)

---

## 👥 Autorzy i licencja

**Autor:** Implementacja wzorców architektonicznych w Symfony  
**Licencja:** MIT  
**Version:** 1.0.0

---

🎯 **Ten projekt demonstruje best practices nowoczesnego developmentu w PHP z wykorzystaniem Symfony i wzorców architektonicznych enterprise-level.**
