```php
<?php 

//test data
/*
$multipleData = array(
   array(
      'id' => '1' ,
      'name' => 'My Name 2' ,
      'date' => 'My date 2'
   ),
   array(
      'id' => '2' ,
      'name' => 'Another Name 2' ,
      'date' => 'Another date 2'
   )
)
*/

/*
 * ----------------------------------
 * update batch 
 * ----------------------------------
 * 
 * multiple update in one query
 *
 * tablename( required | string )
 * multipleData ( required | array of array )
 */
static function updateBatch($tableName = "", $multipleData = array()){

    if( $tableName && !empty($multipleData) ) {

        // column or fields to update
        $updateColumn = array_keys($multipleData[0]);
        $referenceColumn = $updateColumn[0]; //e.g id
        unset($updateColumn[0]);
        $whereIn = "";

        $q = "UPDATE ".$tableName." SET "; 
        foreach ( $updateColumn as $uColumn ) {
            $q .=  $uColumn." = CASE ";

            foreach( $multipleData as $data ) {
                $q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
            }
            $q .= "ELSE ".$uColumn." END, ";
        }
        foreach( $multipleData as $data ) {
            $whereIn .= "'".$data[$referenceColumn]."', ";
        }
        $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";

        // Update  
        return DB::update(DB::raw($q));

    } else {
        return false;
    }
}

/*
It will Produces:

UPDATE `mytable` SET `name` = CASE
WHEN `title` = 'My title' THEN 'My Name 2'
WHEN `title` = 'Another title' THEN 'Another Name 2'
ELSE `name` END,
`date` = CASE 
WHEN `title` = 'My title' THEN 'My date 2'
WHEN `title` = 'Another title' THEN 'Another date 2'
ELSE `date` END
WHERE `title` IN ('My title','Another title')

*/
?>
```
