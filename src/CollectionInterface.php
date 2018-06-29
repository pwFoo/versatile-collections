<?php
namespace VersatileCollections;

/**
 *
 * @author rotimi
 */
interface CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate {

    /**
     * 
     * A factory method to help create a new collection.
     * 
     * The purpose is to act as a shortcut to __construct(...$items)
     * Calling this method eliminates the need to unpack the items .e.g
     * new \VersatileCollections\NumericsCollection(...[1,2,3])
     * vs \VersatileCollections\NumericsCollection::makeNew([1,2,3])
     * 
     * @param array $items an array of items for the new collection to be created. Keys will be preserved in the created collection.
     * @param array $preserve_keys true if keys in $items will be preserved in the created collection.
     * 
     * @return \VersatileCollections\CollectionInterface newly created collection
     * 
     */
    public static function makeNew(array $items=[], $preserve_keys=true);
    
    /**
     * 
     * ArrayAccess: does the requested key exist?
     * 
     * @param string $key The requested key.
     * 
     * @return bool
     * 
     */
    public function offsetExists($key);
    
    /**
     * 
     * ArrayAccess: get a key value.
     * 
     * @param string $key The requested key.
     * 
     * @return mixed
     * 
     */
    public function offsetGet($key);
    
    /**
     * 
     * ArrayAccess: set a key value.
     * 
     * @param string $key The requested key.
     * 
     * @param string $val The value to set it to.
     * 
     * @return void
     * 
     */
    public function offsetSet($key, $val);
    
    /**
     * 
     * ArrayAccess: unset a key.
     * 
     * @param string $key The requested key.
     * 
     * @return void
     * 
     */
    public function offsetUnset($key);
    
    /**
     * 
     * @return array an array containing all items in the collection object
     * 
     */
    public function toArray();
    
    /**
     * 
     * @return \Iterator an iterator
     * 
     */
    public function getIterator();
    
    /**
     * 
     * @return int number of items in collection
     * 
     */
    public function count();
    
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////// OTHER COLLECTION METHODS ////////////////////////////////////////
    
    /**
     * 
     * Retrieves and returns the first item in this collection.
     * 
     * @return mixed The first item in this collection or null if collection is empty.
     * 
     */
    public function firstItem();
    
    /**
     * 
     * Retrieves and returns the last item in this collection.
     * 
     * @return mixed The last item in this collection or null if collection is empty.
     * 
     */
    public function lastItem();
    
    /**
     * 
     * @return array keys to this collection
     * 
     */
    public function getKeys();
    
    /**
     * 
     * @param string $field_name
     * @param mixed $field_val
     * @param bool $add_field_if_not_present
     * 
     * @return $this
     * 
     */
    public function setValForEachItem($field_name, $field_val, $add_field_if_not_present=false);
    
    /**
     * 
     * Filter out items in the collection via a callback function and return filtered items in a new collection.
     * Note that the filtered items are not removed from the original collection.
     * 
     * @param callable $filterer a callback with the following signature
     *                 function($key, $item) that returns true if an item should be filtered out, or false if not
     * 
     * @param bool $copy_keys true if key for each filtered item in $this should be copied into the collection to be returned
     * 
     * @param bool $bind_callback_to_this true if the variable $this inside the supplied 
     *                                    $filterer should refer to the collection object
     *                                    this method is being invoked on, else false if
     *                                    you want the variable $this to be undefined 
     *                                    inside the supplied $filterer.
     * 
     * @param bool $remove_filtered_items true if the filtered items should be removed from
     *                                    the collection this method is being invoked on, 
     *                                    else false if the filtered items should not be 
     *                                    removed from the collection this method is 
     *                                    being invoked on.
     * 
     * @return \VersatileCollections\CollectionInterface a collection of filtered items or an empty collection
     * 
     */
    public function filterAll(callable $filterer, $copy_keys=false, $bind_callback_to_this=true, $remove_filtered_items=false);
    
    /**
     * 
     * Filter out the first N items in the collection via a callback function and return filtered items in a new collection.
     * Note that the filtered items are not removed from the original collection.
     * 
     * @param callable $filterer a callback with the following signature
     *                 function($key, $item) that returns true if an item should be filtered out, or false if not
     * 
     * @param int $max_number_of_filtered_items_to_return Number of filtered items to be returned. Null means return all filtered items
     * 
     * @param bool $copy_keys true if key for each filtered item in $this should be copied into the collection to be returned
     * 
     * @param bool $bind_callback_to_this true if the variable $this inside the supplied 
     *                                    $filterer should refer to the collection object
     *                                    this method is being invoked on, else false if
     *                                    you want the variable $this to be undefined 
     *                                    inside the supplied $filterer.
     * 
     * @param bool $remove_filtered_items true if the filtered items should be removed from
     *                                    the collection this method is being invoked on, 
     *                                    else false if the filtered items should not be 
     *                                    removed from the collection this method is 
     *                                    being invoked on.
     * 
     * @return \VersatileCollections\CollectionInterface a collection of filtered items or an empty collection
     * 
     */
    public function filterFirstN(callable $filterer, $max_number_of_filtered_items_to_return=null, $copy_keys=false, $bind_callback_to_this=true, $remove_filtered_items=false);
    
    /**
     * 
     * Transform each item in the collection via a callback function.
     * 
     * @param callable $transformer a callback with the following signature
     *                 function($key, $item) that returns a value that will replace $this[$key]
     * 
     * @param bool $bind_callback_to_this true if the variable $this inside the supplied 
     *                                    $transformer should refer to the collection object
     *                                    this method is being invoked on, else false if
     *                                    you want the variable $this to be undefined 
     *                                    inside the supplied $transformer.
     * 
     * @return $this
     * 
     */
    public function transform(callable $transformer, $bind_callback_to_this=true);
    
    /**
     * 
     * Iteratively reduce the collection items to a single value using a callback function.
     * 
     * @see mixed array_reduce( array $array, callable $callback [, mixed $initial = NULL ] )
     * 
     * @param callable $reducer function(mixed $carry , mixed $item): mixed
     *                          $carry: Holds the return value of the previous iteration; in the case of the first iteration it instead holds the value of initial.
     *                           $item: Holds the value of the current iteration.
     * 
     * @param mixed $initial_value If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the collection is empty.
     * 
     * @return mixed a value that all items in the collection have been reduced to by applying the $reducer callback on each item.
     *  
     */
    public function reduce(callable $reducer, $initial_value=NULL);
    
    /**
     * 
     * Iteratively reduce the collection items to a single value using a callback function.
     * The callback function will have access to the key for each item.
     *  
     * @param callable $reducer function(mixed $carry , mixed $item, string|int $key): mixed
     *                          $carry: Holds the return value of the previous iteration; in the case of the first iteration it instead holds the value of initial.
     *                           $item: Holds the value of the current iteration.
     *                            $key: Holds the coresponding key of the current iteration.
     * 
     * @param mixed $initial_value If the optional initial is available, it will be used at the beginning of the process, or as a final result in case the collection is empty.
     * 
     * @return mixed a value that all items in the collection have been reduced to by applying the $reducer callback on each item.
     *  
     */
    public function reduceWithKeyAccess(callable $reducer, $initial_value=NULL);
    
    /**
     * 
     * Reverse order of items in the collection and return the reversed items in a new collection.
     * 
     * @return \VersatileCollections\CollectionInterface a collection of reversed items
     * 
     */
    public function reverse();
    
    /**
     * 
     * Reverse order of items in the collection. Original collection will be modified.
     * 
     * @return $this
     * 
     */
    public function reverseMe();
    
    /**
     * 
     * Return true if there are one or more items in the collection or false otherwise
     * 
     * @return bool
     * 
     */
    public function isEmpty();
    
    /**
     * 
     * Try to get an item with the specified key ($key) or return $default_value if key does not exist.
     * 
     * @param string|int $key
     * @param mixed $default_value
     * 
     * @return mixed
     * 
     */
    public function getIfExists($key, $default_value=null);
    
    /**
     * 
     * Check if a collection contains an item using strict comparison.
     * 
     * @param mixed $item item whose existence in the collection is to be checked
     * 
     * @return bool true if collection contains item, false otherwise
     * 
     */
    public function containsItem($item);
    
    /**
     * 
     * Check if a collection contains an item with the specified key using strict comparison for the item.
     * 
     * @param string|int $key key whose existence in the collection is to be checked
     * @param mixed $item item whose existence in the collection is to be checked
     * 
     * @return bool true if collection contains item with the specified key, false otherwise
     * 
     * @throws \InvalidArgumentException if $key is non-int and non-string
     * 
     */
    public function containsItemWithKey($key, $item);
    
    /**
     * 
     * Check if all the specified items exist in a collection
     * 
     * @param array $items specified items whose existence is to be checked in the collection 
     * 
     * @return bool true if all specified items exist in collection, false otherwise
     * 
     */
    public function containsItems(array $items);
    
    /**
     * 
     * Check if a key exists in a collection
     * 
     * @param mixed $key key whose existence in the collection is to be checked
     * 
     * @return bool true if key exists in collection, false otherwise
     * 
     * @throws \InvalidArgumentException if $key is non-int and non-string
     * 
     */
    public function containsKey($key);
    
    /**
     * 
     * Check if all the specified keys exist in a collection
     * 
     * @param array $keys specified keys whose existence is to be checked in the collection 
     * 
     * @return bool true if all specified keys exist in collection, false otherwise
     * 
     */
    public function containsKeys(array $keys);
    
    /**
     * 
     * Appends all items from $other collection to the end of $this collection. Note that appended items will be assigned numeric keys.
     * 
     * @param \VersatileCollections\CollectionInterface $other
     * 
     * @return $this
     * 
     */
    public function appendCollection(CollectionInterface $other);
    
    /**
     * 
     * Appends an $item to the end of $this collection.
     * 
     * @param mixed $item
     * 
     * @return $this
     * 
     */
    public function appendItem($item);
    
    /**
     * 
     * Prepends all items from $other collection to the front of $this collection. Note that all numerical keys will be modified to start counting from zero while literal keys won't be changed.
     * 
     * @param \VersatileCollections\CollectionInterface $other
     * 
     * @return $this
     * 
     */
    public function prependCollection(CollectionInterface $other);
    
    /**
     * 
     * Prepends an $item to the front of $this collection.
     * 
     * @param mixed $item
     * @param string|int $key
     * 
     * @return $this
     * 
     */
    public function prependItem($item, $key=null);
    
    /**
     * 
     * Adds all items from $items to $this collection and returns a new collection
     * containing the result. The original collection will not be modified.
     * Items in $items with existing keys in $this will overwrite the existing items in $this.
     * 
     * Use union() and unionMeWith() if you want items from $this to be used
     * when same keys exist in both $items and $this.
     * 
     * @see \VersatileCollections\CollectionInterface::union($items)
     * @see \VersatileCollections\CollectionInterface::unionMeWith($items)
     * 
     * @param array $items
     * 
     * @return \VersatileCollections\CollectionInterface a new collection containing 
     *                                                   the result of merging all 
     *                                                   items from $items with 
     *                                                   $this collection
     */
    public function mergeWith(array $items);
    
    /**
     * 
     * Adds all items from $items to $this collection. 
     * Items in $items with existing keys in $this will overwrite the existing items in $this.
     * 
     * Use union() and unionMeWith() if you want items from $this to be used
     * when same keys exist in both $items and $this.
     * 
     * @see \VersatileCollections\CollectionInterface::union($items)
     * @see \VersatileCollections\CollectionInterface::unionMeWith($items)
     * 
     * @param array $items
     * 
     * @return $this
     * 
     */
    public function mergeMeWith(array $items);
    
    /**
     * 
     * Returns a generator that yields collections each having a maximum of $num_of_items. Original keys are preserved in each returned collection.
     * 
     * If $this contains [1,2,3,4,5,6]
     * 
     * foreach( $this->getCollectionsOfSizeN(2) as $batch ) {
     * 
     *     var_dump($batch);
     * }
     * 
     * will output
     * 
     * [0=>1,1=>2]
     * [2=>3,3=>4]
     * [4=>5,5=>6]
     * 
     * @param int $max_size_of_each_collection
     * 
     * @return \Generator a generator that yields sub-collections
     * 
     */
    public function getCollectionsOfSizeN($max_size_of_each_collection=1);
    
    /**
     * 
     * Convert all keys in the collection to consecutive integer keys starting from $starting_key 
     * 
     * @param int $starting_key a positive integer value that will be the value of the first key. 
     *                          A negative integer value should be forced to zero.
     * 
     * @return $this
     * 
     * @throws \InvalidArgumentException if $starting_key is not an integer
     * 
     */
    public function makeAllKeysNumeric($starting_key=0);
    
    /**
     * 
     * Create a new collection with all the items in the original collection.
     * All the keys in the new collection will be consecutive integer keys 
     * starting from zero.
     *
     * @return \VersatileCollections\CollectionInterface new collection with all the items in the original collection
     */
    public function values();
    
    /**
     * 
     * Iterate through a collection and execute a callback over each item during the iteration.
     * 
     * @param callable $callback a callback with the following signature
     *                           function($key, $item). To stop iteration at any
     *                           point, the callback should return the value 
     *                           specified via $termination_value. For example,
     *                           if you wanted to loop through the first half of
     *                           a collection you 
     * 
     * @param mixed $termination_value a value that should be returned by $callback 
     *                                 signifying that iteration through a collection
     *                                 should stop.
     * 
     * @param bool $bind_callback_to_this true if the variable $this inside the supplied 
     *                                    $callback should refer to the collection object
     *                                    this method is being invoked on, else false if
     *                                    you want the variable $this to be undefined 
     *                                    inside the supplied $callback.
     * 
     * @return $this
     * 
     */
    public function each(callable $callback, $termination_value=false, $bind_callback_to_this=true);
    
    /**
     * 
     * Applies the callback to the items in the collection and returns a new 
     * collection containing all the items in the original collection after 
     * applying the callback function to each one.
     * 
     * 
     * 
     * @param callable $callback a callback with the following signature
     *                           function($key, $item). It should perform an
     *                           operation on each item and return the result 
     *                           of the operation on each item.
     *                          
     * @param bool $preserve_keys true if keys in the returned collection should 
     *                            match the keys in the original collection, else
     *                            false for sequentially incrementing integer keys 
     *                            (starting from 0) in the returned collection.
     * 
     * @param bool $bind_callback_to_this true if the variable $this inside the supplied 
     *                                    $callback should refer to the collection object
     *                                    this method is being invoked on, else false if
     *                                    you want the variable $this to be undefined 
     *                                    inside the supplied $callback.
     * 
     * @return \VersatileCollections\CollectionInterface
     * 
     */
    public function map(callable $callback, $preserve_keys=true, $bind_callback_to_this=true);
    
    /**
     * 
     * Create a new collection consisting of every n-th element.
     *
     * @param  int  $n the number representing n
     * @param  int  $position_of_first_nth_item position in the collection to 
     *                                          start counting for the nth elements. 
     *                                          0 represents the position of 
     *                                          the first item in the collection.
     * 
     * @return \VersatileCollections\CollectionInterface (a new collection consisting of every n-th element)
     * 
     */
    public function everyNth($n, $position_of_first_nth_item = 0);
    
    /**
     * 
     * Pass the collection to the given callback and return whatever value is
     * returned from executing the given callback.
     *
     * @param  callable $callback a callback with the following signature
     *                            function($collection). The $collection 
     *                            argument in the callback's signature is
     *                            collection object this 
     *                            pipeAndReturnCallbackResult 
     *                            method is being invoked on.
     * 
     * @return mixed whatever is returned by $callback
     * 
     */
    public function pipeAndReturnCallbackResult(callable $callback);
    
    /**
     * 
     * Pass the collection to the given callback and return the collection.
     *
     * @param  callable $callback a callback with the following signature
     *                            function($collection). The $collection 
     *                            argument in the callback's signature is
     *                            collection object this pipeAndReturnSelf 
     *                            method is being invoked on.
     * 
     * @return $this
     * 
     */
    public function pipeAndReturnSelf(callable $callback);
    
    /**
     * 
     * Get and remove the last item from the collection.
     *
     * @return mixed
     * 
     */
    public function getAndRemoveLastItem();
    
    /**
     * 
     * Get and remove an item from the collection.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     * @return mixed
     * 
     */
    public function pull($key, $default = null);
    
    /**
     * 
     * Alias of appendItem($item)
     *
     * @param  mixed  $item
     * @return $this
     * 
     */
    public function push($item);
    

    /**
     * 
     * Put an item in the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * 
     * @return $this
     * 
     */
    public function put($key, $value);
    
    /**
     * 
     * Get one key randomly from the collection.
     * A length exception (\LengthException) should be thrown if this method is called on an empty collection.
     * 
     * @return mixed a random key from the collection if there is at least an item in the collection
     * 
     * @throws \LengthException
     *
     */
    public function randomKey();
    
    /**
     * 
     * Get one item randomly from the collection.
     * A length exception (\LengthException) should be thrown if this method is called on an empty collection.
     * 
     * @return mixed a random item from the collection if there is at least an item in the collection
     * 
     * @throws \LengthException
     *
     */
    public function randomItem();

    /**
     * 
     * Get a specified number of unique keys randomly from the collection and return them in a new collection.
     * 
     * A \LengthException should be thrown if this method is called on an empty collection.
     * An \InvalidArgumentException should be thrown if $number is either not an int or if it is bigger than the number of items in the collection.
     *
     * @param int $number number of random keys to be returned
     * 
     * @return \VersatileCollections\GenericCollection (a new collection containing the random keys)
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * 
     */
    public function randomKeys($number = 1);

    /**
     * 
     * Get a specified number of items randomly from the collection and return them in a new collection.
     * 
     * A \LengthException should be thrown if this method is called on an empty collection.
     * An \InvalidArgumentException should be thrown if $number is either not an int or if it is bigger than the number of items in the collection.
     *
     * @param int $number number of random items to be returned
     * @param bool $preserve_keys true if the key associated with each random item should be used in the new collection returned by this method,
     *                            otherwise false if the new collection returned should have sequential integer keys starting at zero.
     * 
     * @return \VersatileCollections\CollectionInterface (a new collection containing the random items)
     *
     * @throws \InvalidArgumentException
     * @throws \LengthException
     * 
     */
    public function randomItems($number = 1, $preserve_keys=false);
    
    /**
     * 
     * Shuffle all the items in the collection and return shuffled items in a new collection.
     * If collection is empty, this method should also return an empty collection.
     * 
     * @param bool $preserve_keys true if the key associated with each shuffled item should be used in the new collection returned by this method,
     *                            otherwise false if the new collection returned should have sequential integer keys starting at zero.
     * 
     * @return \VersatileCollections\CollectionInterface (a new collection containing the shuffled items)
     * 
     */
    public function shuffle($preserve_keys=true);

        /**
     * 
     * Search the collection for a given value and return the first 
     * corresponding key if successful.
     * 
     * @param mixed $value the value to be searched for
     * @param bool $strict true if strict comparison should be used when searching, 
     *                          else false for loose comparison
     * 
     * @return mixed the first key in the collection whose item matches $value 
     *               or false if $value is not found in the collection
     * 
     */
    public function searchByVal( $value, $strict = false );

    /**
     * 
     * Search the collection for a given value and return an array of all 
     * corresponding key(s) in the collection whose item(s) match the value, 
     * if successful.
     * 
     * @param mixed $value the value to be searched for
     * @param bool $strict true if strict comparison should be used when searching, 
     *                          else false for loose comparison
     * 
     * @return mixed an array of all key(s) in the collection whose item(s) match $value 
     *               or false if $value is not found in the collection
     * 
     */
    public function searchAllByVal( $value, $strict = false );


    /**
     * 
     * Search the collection using a callback. The callback will be executed on
     * each item and corresponding key in the collection. Returns an array of all 
     * corresponding key(s) in the collection for which the callback returns
     * true.
     * 
     * @param callable $callback a callback with the following signature
     *                           function($key, $item). It should return true
     *                           if a $key should be returned or false otherwise.
     *  
     * @param bool $bind_callback_to_this true if the variable $this inside the supplied 
     *                                    $callback should refer to the collection object
     *                                    this method is being invoked on, else false if
     *                                    you want the variable $this to be undefined 
     *                                    inside the supplied $callback.
     * 
     * @return mixed an array of all key(s) in the collection for which the callback 
     *               returned true or false if the callback did not return true for 
     *               any iteration over the collection
     * 
     */
    public function searchByCallback($callback, $bind_callback_to_this=true);
    

    /**
     * 
     * Get and remove the first item from the collection.
     *
     * @return mixed
     * 
     */
    public function getAndRemoveFirstItem ();
    
    /**
     * 
     * Extract a slice of the collection.
     * 
     * The collection itself should not be modified (i.e. sliced items will 
     * still remain in the collection this method is being called with).
     * 
     * @see array_slice http://php.net/manual/en/function.array-slice.php
     * 
     * @param int $offset If offset is non-negative, the sequence will start at 
     *                    that offset in the array. If offset is negative, the 
     *                    sequence will start that far from the end of the array.
     * 
     * @param int $length If length is given and is positive, then the sequence 
     *                    will have up to that many elements in it. If the array 
     *                    is shorter than the length, then only the available 
     *                    array elements will be present. If length is given and 
     *                    is negative then the sequence will stop that many 
     *                    elements from the end of the array. If it is omitted, 
     *                    then the sequence will have everything from offset up 
     *                    until the end of the array.
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sliced items
     * 
     * @throws \InvalidArgumentException if $offset is non-int and / or if $length is non-null and non-int
     * 
     */
    public function slice($offset, $length = null);
    
    /**
     * 
     * Sort the collection's items in ascending order while maintaining key association.
     * A new collection containing the sorted items is returned.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           first argument is considered to be respectively 
     *                           less than, equal to, or greater than the second.
     *                           If callback is not supplied, a native php sorting
     *                           function that maintains key association should be
     *                           used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sorted items
     * 
     */
    public function sort(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort the collection's items in descending order while maintaining key association.
     * A new collection containing the sorted items is returned.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           second argument is considered to be respectively 
     *                           less than, equal to, or greater than the first.
     *                           If callback is not supplied, a native php sorting
     *                           function that maintains key association should be
     *                           used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sorted items
     * 
     */
    public function sortDesc(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort the collection's items by keys in ascending order while maintaining key association.
     * A new collection containing the sorted items is returned.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           first argument is considered to be respectively 
     *                           less than, equal to, or greater than the second.
     *                           If callback is not supplied, a native php sorting
     *                           function that sorts by key and maintains key 
     *                           association should be used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sorted items
     * 
     */
    public function sortByKey(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort the collection's items by keys in descending order while maintaining key association.
     * A new collection containing the sorted items is returned.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           second argument is considered to be respectively 
     *                           less than, equal to, or greater than the first.
     *                           If callback is not supplied, a native php sorting
     *                           function that sorts by key and maintains key 
     *                           association should be used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sorted items
     * 
     */
    public function sortDescByKey(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort a collection of associative arrays or objects that implement \ArrayAccess by 
     * specified field name(s) and return a new collection containing the sorted items
     * with their original key associations preserved.
     * 
     * This method should throw a \RuntimeException if any of the items in the 
     * collection is not an associative array or an object that implements \ArrayAccess.
     * 
     * Example:
     * $data = [];
     * $data[0] = [ 'volume' => 67, 'edition' => 2 ]; <br />
     * $data[1] = [ 'volume' => 86, 'edition' => 1 ]; <br />
     * $data[2] = [ 'volume' => 85, 'edition' => 6 ]; <br />
     * 
     * $collection = new \VersatileCollections\GenericCollection(...$data);
     * $sort_param = new \VersatileCollections\MultiSortParameters('volume', SORT_ASC, SORT_NUMERIC);
     * $sorted_collection = $collection->SortByMultipleFields($sort_param);
     * 
     * // $sorted_collection->toArray() will look like:
     * 
     * [
     *      0 => [ 'volume' => 67, 'edition' => 2 ], 
     *      2 => [ 'volume' => 85, 'edition' => 6 ], 
     *      1 => [ 'volume' => 86, 'edition' => 1 ], 
     * ]
     * 
     * @param \VersatileCollections\MultiSortParameters $param
     * ........
     * ........
     * @param \VersatileCollections\MultiSortParameters $param
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sorted items
     * 
     */
    public function sortByMultipleFields(\VersatileCollections\MultiSortParameters ...$param);
    
    /**
     * 
     * Sort the collection's items in ascending order while maintaining key association.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           first argument is considered to be respectively 
     *                           less than, equal to, or greater than the second.
     *                           If callback is not supplied, a native php sorting
     *                           function that maintains key association should be
     *                           used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return $this
     * 
     */
    public function sortMe(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort the collection's items in descending order while maintaining key association.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           second argument is considered to be respectively 
     *                           less than, equal to, or greater than the first.
     *                           If callback is not supplied, a native php sorting
     *                           function that maintains key association should be
     *                           used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return $this
     * 
     */
    public function sortMeDesc(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort the collection's items by keys in ascending order while maintaining key association.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           first argument is considered to be respectively 
     *                           less than, equal to, or greater than the second.
     *                           If callback is not supplied, a native php sorting
     *                           function that sorts by key and maintains key 
     *                           association should be used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return $this
     * 
     */
    public function sortMeByKey(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort the collection's items by keys in descending order while maintaining key association.
     * 
     * @param callable $callable a callback with the following signature
     *                           function(mixed $a, mixed $b): int. 
     *                           The callback function must return an INTEGER 
     *                           less than, equal to, or greater than zero if the 
     *                           second argument is considered to be respectively 
     *                           less than, equal to, or greater than the first.
     *                           If callback is not supplied, a native php sorting
     *                           function that sorts by key and maintains key 
     *                           association should be used for the sorting.
     * 
     * @param \VersatileCollections\SortType $type an object indicating the sort type.
     *                                             See \VersatileCollections\SortType::$valid_sort_types
     *                                             for available sort types.
     * 
     * @return $this
     * 
     */
    public function sortMeDescByKey(callable $callable=null, \VersatileCollections\SortType $type=null);
    
    /**
     * 
     * Sort a collection of associative arrays or objects that implement \ArrayAccess by 
     * specified field name(s) while preserving original key associations.
     * 
     * This method should throw a \RuntimeException if any of the items in the 
     * collection is not an associative array or an object that implements \ArrayAccess.
     * 
     * Example:
     * $data = [];
     * $data[0] = [ 'volume' => 67, 'edition' => 2 ]; <br />
     * $data[1] = [ 'volume' => 86, 'edition' => 1 ]; <br />
     * $data[2] = [ 'volume' => 85, 'edition' => 6 ]; <br />
     * 
     * $collection = new \VersatileCollections\GenericCollection(...$data);
     * $sort_param = new \VersatileCollections\MultiSortParameters('volume', SORT_ASC, SORT_NUMERIC);
     * $collection->SortMeByMultipleFields($sort_param);
     * 
     * // $collection->toArray() will look like:
     * 
     * [
     *      0 => [ 'volume' => 67, 'edition' => 2 ], 
     *      2 => [ 'volume' => 85, 'edition' => 6 ], 
     *      1 => [ 'volume' => 86, 'edition' => 1 ], 
     * ]
     * 
     * @param \VersatileCollections\MultiSortParameters $param
     * ........
     * ........
     * @param \VersatileCollections\MultiSortParameters $param
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing the sorted items
     * 
     */
    public function sortMeByMultipleFields(\VersatileCollections\MultiSortParameters ...$param);

    /**
     * 
     * Remove a portion of the collection and optionally replace with items in $replacement.
     * This method modifies the original collection.
     * 
     * @see array_splice http://php.net/manual/en/function.array-splice.php
     *
     * @param  int $offset If offset is positive then the start of removed portion 
     *                     is at that offset from the beginning of the collection. 
     *                     If offset is negative then it starts that far from the 
     *                     end of the collection.
     * 
     * @param  int|null $length If length is omitted, removes everything from offset 
     *                          to the end of the collection. If length is specified  
     *                          & is positive, then that many elements will be removed. 
     *                          If length is specified and is negative then the end 
     *                          of the removed portion will be that many elements from 
     *                          the end of the collection. If length is specified and 
     *                          is zero, no elements will be removed. Tip: to remove 
     *                          everything from offset to the end of the collection 
     *                          when replacement is also specified, use $this->count() 
     *                          for length.
     * 
     * @param  array $replacement If replacement array is specified, then the removed items 
     *                            are replaced with items from this array.
     * 
     *                            If offset and length are such that nothing is removed, 
     *                            then the items from the replacement array are inserted 
     *                            in the place specified by the offset. Note that keys in 
     *                            replacement array are not preserved.
     *                            
     * @return \VersatileCollections\CollectionInterface A new collection containing the removed items.
     * 
     * @throws \InvalidArgumentException if $offset is non-int and / or if $length is non-null and non-int
     * 
     */
    public function splice($offset, $length=null, array $replacement=[]);
    
    /**
     * 
     * Split a collection into a certain number of groups.
     * 
     * Throw an excption if 
     *      !is_int($numberOfGroups) or
     *      $numberOfGroups > $this->count() or
     *      $numberOfGroups < 0
     *
     * @param  int $numberOfGroups
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing $numberOfGroups collections
     *
     * @throws \InvalidArgumentException
     * 
     */
    public function split($numberOfGroups);
    

    /**
     * 
     * Take the first or last {$limit} items and return them in a new collection.
     * The items will not be removed from the original collection.
     *
     * @param  int  $limit If positive, then first {$limit} items will be returned.
     *                     If negative, then last {$limit} items will be returned.
     *                     If zero, then empty collection will be returned.
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing first or last {$limit} items.
     * 
     * @throws \InvalidArgumentException
     * 
     */
    public function take($limit);

    /**
     * 
     * Union the collection with the given items by trying to append all items 
     * from $items to $this collection and return the result in a new collection.
     * 
     * For keys that exist in both $items and $this, the items from $this 
     * will be used, and the matching items from $items will be ignored.
     * 
     * For example:
     *  $a = \VersatileCollections\GenericCollection::makeNew( 
     *          [ "a"=>"apple", "b"=>"banana" ]
     *      );
     * $b = [ "a"=>"pear", "b"=>"strawberry", "c"=>"cherry" ];
     * 
     * // result
     * $a->union($b)->toArray() === [ "a"=>"apple", "b"=>"banana", "c"=>"cherry" ]
     *  
     * 
     * Use merge() and mergeMeWith() if you want items from $items to be used
     * when same keys exist in both $items and $this.
     *
     * @see \VersatileCollections\CollectionInterface::merge($items)
     * @see \VersatileCollections\CollectionInterface::mergeMeWith($items)
     * 
     * This method does not modify the original collection.
     * 
     * @param  array $items
     * 
     * @return \VersatileCollections\CollectionInterface A new collection containing items in the original collection unioned with $items.
     * 
     */
    public function unionWith(array $items);

    /**
     * 
     * Union the collection with the given items by trying to append all items 
     * from $items to $this collection.
     * 
     * For keys that exist in both $items and $this, the items from $this 
     * will be used, and the matching items from $items will be ignored.
     * 
     * For example:
     *  $a = \VersatileCollections\GenericCollection::makeNew( 
     *          [ "a"=>"apple", "b"=>"banana" ]
     *      );
     * $b = [ "a"=>"pear", "b"=>"strawberry", "c"=>"cherry" ];
     * 
     * // result
     * $a->union($b)->toArray() === [ "a"=>"apple", "b"=>"banana", "c"=>"cherry" ]
     *  
     * 
     * Use merge() and mergeMeWith() if you want items from $items to be used
     * when same keys exist in both $items and $this.
     * 
     * @see \VersatileCollections\CollectionInterface::merge($items)
     * @see \VersatileCollections\CollectionInterface::mergeMeWith($items)
     * 
     * This method modifies the original collection.
     *
     * @param  array $items
     * 
     * @return $this
     * 
     */
    public function unionMeWith(array $items);
    
    /**
     * 
     * Get a collection of unique items from an existing collection. The keys
     * are not preserved in the returned collection. The uniqueness test must be
     * done via strict comparison (===). 
     * 
     * Non-strict comparison is unsafe for collections containing objects, for 
     * example you can't cast an object to a double or int. To get unique items 
     * using non-strict comparison see 
     * \VersatileCollections\ScalarCollection::uniqueNonStrict().
     * 
     * @see \VersatileCollections\ScalarCollection::uniqueNonStrict() 
     * 
     * @return \VersatileCollections\CollectionInterface
     * 
     */
    public function unique();
    
    /**
     * 
     * Execute $callback on $this and return its return value if $truthy_value is truthy
     * or execute $default on $this and return its return value if $default is not null
     * or return $this as a last resort.
     * 
     * @param bool $truthy_value
     * 
     * @param callable $callback a callback with the following signature
     *                           function(\VersatileCollections\CollectionInterface $collection, $truthy_value)
     *                           It will be invoked on the collection object from which this method
     *                           is being called.
     * 
     * @param callable|null $default a callback with the following signature
     *                               function(\VersatileCollections\CollectionInterface $collection, $truthy_value)
     *                               It will be invoked on the collection object from which this method
     *                               is being called. 
     *                               If $default is null and $truthy_value is not truthy, $this will
     *                               be returned by this method.
     * 
     * @return mixed
     * 
     */
    public function whenTrue( $truthy_value, callable $callback, callable $default=null);
    
    /**
     * 
     * Execute $callback on $this and return its return value if $falsy_value is falsy
     * or execute $default on $this and return its return value if $default is not null
     * or return $this as a last resort.
     * 
     * @param bool $falsy_value
     * 
     * @param callable $callback a callback with the following signature
     *                           function(\VersatileCollections\CollectionInterface $collection, $falsy_value)
     *                           It will be invoked on the collection object from which this method
     *                           is being called.
     * 
     * @param callable|null $default a callback with the following signature
     *                               function(\VersatileCollections\CollectionInterface $collection, $falsy_value)
     *                               It will be invoked on the collection object from which this method
     *                               is being called. 
     *                               If $default is null and $falsy_value is not falsy, $this will
     *                               be returned by this method.
     * 
     * @return mixed
     * 
     */
    public function whenFalse( $falsy_value, callable $callback, callable $default=null);
    
    /**
     * 
     * Return the values from a single column in the collection.
     * 
     * Will only work on collections containing items that are 
     * arrays and/or objects.
     * 
     * Must throw an exception if either $column_key and / or $index_key 
     * contain(s) non-string and non-int value(s). 
     * NOTE: $index_key can be null, meaning that the collection returned by 
     * this method will have sequential integer keys starting at 0.
     * 
     * Must throw an exception if any item in the collection is not an array
     * or object.
     *  
     * Must throw an exception if $column_key is not a key in at least one array
     * in the collection. 
     * 
     * Must throw an exception if $column_key is not an accessible property in 
     * at least one object in the collection.
     *
     * Must throw an exception if $index_key is not null and is not a key in 
     * at least one array in the collection.
     * 
     * Must throw an exception if $index_key is not null and is not an 
     * accessible property in at least one object in the collection.
     *
     * Must throw an exception if $index_key is not null and at least one 
     * array in the collection has a non-int and non-string value for the 
     * element with key $index_key.
     *
     * Must throw an exception if $index_key is not null and at least one 
     * object in the collection has a non-int and non-string value for the
     * property named $index_key.
     * 
     * @param string|int $column_key name of field in each item to be used as values / items in the collection to be returned
     * @param string|int $index_key name of field in each item to be used as key in the collection to be returned.
     *                              If null, the returned collection will have sequential integer keys starting from 0.
     *                              Be aware that only string or integer values are usuable as keys in the collection
     *                              to be returned by this method and as a result an excpetion will be thrown if any
     *                              item in the collection has a non-string and non-integer value for the field 
     *                              specified in $index_key.
     * 
     * @return \VersatileCollections\GenericCollection A new collection containing the values from a single column in this collection
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * 
     */
    public function column($column_key, $index_key=null);
    
    /**
     * 
     * Create a new collection of the specified type with the keys and items in this collection.
     * Original collection should not be modified.
     * 
     * @param string|\VersatileCollections\CollectionInterface $new_collection_class name of a collection class that implements
     *                                                                               \VersatileCollections\CollectionInterface or an 
     *                                                                               instance of \VersatileCollections\CollectionInterface
     * 
     * @return \VersatileCollections\CollectionInterface a new collection of the specified type 
     *                                                   containing the exact same keys and items 
     *                                                   as the original collection.
     * 
     * @throws \VersatileCollections\Exceptions\InvalidItemException if one or more items in the original collection does not satisfy
     *                                                               the specified new type. For example you cannot get a collection 
     *                                                               of Objects as a collection of Floats.
     * 
     * @throws \InvalidArgumentException if $new_collection_class is not a string and is not an object
     *                                   of if $new_collection_class is not an instanceof \VersatileCollections\CollectionInterface
     * 
     */
    public function getAsNewType($new_collection_class=GenericCollection::class);
    
    /**
     * 
     * Remove all items from the collection and return $this. 
     * The internal array should be set to an empty array.
     * 
     * @return $this
     * 
     */
    public function removeAll();
}
