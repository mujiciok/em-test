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

### Build the docker image and run the script
```
docker build -qt emendis-test . 
docker run --rm emendis-test
```

### Alternatively, run the PHP script directly
```
php index.php
```

## Questions / Thoughts

#### Minimal number of containers

"find a good solution to fit all packages in as few containers as possible." - I assume it's not just about the quantity
of the containers, but also the least possible volume (as "quantity only" will mean I should use only the biggest size container).

#### Mixing types of containers

Not sure about different type of containers - should I consider mixing types of containers? 
If so, it adds additional complexity to the calculations. Skipping this logic due to a lack of time.
2 approaches: 
- order containers by volume in DESCENDING order, then last container should be checked if it can fit smaller containers;
- order containers by volume in ASCENDING order, then once a container is full and there are more boxes to add, swap to the next by size container.

Note: Potentially, there could be issues with containers of shapes that are too different. 
It should work with current 2 types of containers, as the smaller one has same width and height. 

#### Mixing boxes' rotated positions

There could be cases when mixing boxes rotated positions will optimize the space used. 
Skipping this logic due to its complexity. 

Note: The easiest way of "mixing" would be creating "walls" of boxes in different positions,
e.g. having 3 rows of boxes placed horizontally and the 4th row placed vertically.

#### Unit size

"cm" probably is not the best unit size, as it requires working with float numbers. 
If it was saved in a database, "mm" (integer type) would be better, or even smaller units, if such precision matters.