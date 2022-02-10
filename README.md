# Paysera Task


## Run Locally

Clone the project

```bash
  https://github.com/sina3p2p/psr-test.git
```

Go to the project directory

```bash
  cd psr-test
```

Install dependencies

```bash
  composer install
```

Start the server

```bash
  php artisan serve
```


## Running Tests

To run tests, run the following command

```bash
  php artisan test
```


## Usage/Examples

```php
  $calculator = new CommissionCalculation();

  // Set cutsom rate (Optional)
  $calculator->setCustomCurrency([
      'JPY' => 129.53,
      'USD' => 1.1497,
  ]);

  $calculator->addTransaction(new Transaction($input));
        
  $calculator->calculate();
```


## Lessons Learned

You can test it after run serve at this url http://127.0.0.1:8000/test and it's return commissions in today currency rate otherwise if you want to check output by test result what is written in doc (EUR:USD - 1:1.1497, EUR:JPY - 1:129.53.) you can run following url:
http://127.0.0.1:8000/test?test=1

You can also check logic:
```bash
App\Http\Controller\TestController.php
```

You can also change commission/ max free transaction amount per week:
```bash
App\Helpers\CommissionCalculation.php
```