# QBO Helpers

**QuickBooks Online PHP Helper Package**

A lightweight PHP library that provides an easy-to-use interface for interacting with the QuickBooks Online API. This package includes both a low-level service class for direct API calls and a fluent facade for simple, chainable operations.

---

## **Purpose**

- Simplifies QuickBooks Online API integration in PHP projects.
- Provides CRUD operations (`create`, `update`, `delete`, `findAll`, `findById`) for QBO entities.
- Supports raw queries and filtering.
- Handles sandbox and production environments.
- Compatible with PHP 7.4+.

---

## **Installation**

Install via Composer:

```bash
composer require felconca/qbo-helpers
```

**Manual installation:**

1. Download or clone the repository.
2. Include the `autoload.php` or `QuickBooksOnline.php` files manually (Composer is preferred).

## **Helpers Usage**

#### **1. Initialize QBO**

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use QuickBooksOnlineHelper\Facades\QBO;

QBO::setAuth('YOUR_COMPANY_ID', 'YOUR_ACCESS_TOKEN', true); // last param: sandbox (true/false)
```

#### **2. Fetch all entities**

```php
$customers = QBO::all()->Customer();
echo json_encode($customers['data']);
```

### **3. Fetch entity by ID**

```php
$invoice = QBO::get()->Invoice(101); // 101 = entity ID
echo json_encode($invoice['data']);
```

### **4. Create a new entity**

```php
$data = [
    "DocNumber" => "101",
    "Line" => [
        [
            "Description" => "Sewing Service for Alex",
            "Amount" => 150.00,
            "DetailType" => "SalesItemLineDetail",
            "SalesItemLineDetail" => [
                "ItemRef" => ["value" => 1, "name" => "Services"]
            ]
        ]
    ],
    "CustomerRef" => ["value" => "1", "name" => "Alex"]
];

$newInvoice = QBO::create()->Invoice($data);
echo json_encode($newInvoice['data']);
```

### **5. Update an entity**

```php
$updateData = [
    "Id" => "101",
    "SyncToken" => "0",
    "DocNumber" => "102"
];

$updatedInvoice = QBO::update()->Invoice($updateData);
echo json_encode($updatedInvoice['data']);
```

### **6. Delete an entity**

```php
$deletedInvoice = QBO::delete()->Invoice("101", "0"); // ID, SyncToken
echo json_encode($deletedInvoice['data']);
```

### **7. Run a custom query**

```php
$invoices = QBO::query()->Invoice("WHERE DocNumber = '101'");
echo json_encode($invoices['data']);
```

## **Features**

- Fluent interface for chainable API calls.
- Automatic handling of sandbox and production endpoints.
- PHP 7.4+ compatible.
- Supports all main QBO entities: `Invoice`, `Customer`, `Item`, etc.
- Query builder support for custom SQL-like queries.

### **Project is currently private & copyright protected © [Felcon Albaladejo]**
