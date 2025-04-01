## Setup

### Clone repository
```
git clone git@github.com:mujiciok/em-test.git
cd em-test
```

### Generate autoload files
```
composer install
```

### Build the image and run the script
```
docker build -qt emendis-test . 
docker run --rm emendis-test
```

## Additional info

"find a good solution to fit all packages in as few containers as possible." - I assume it's not just about the quantity
of the containers, but also the least possible volume (as "quantity only" will mean I should use only the biggest size container).
