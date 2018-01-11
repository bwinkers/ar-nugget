<?php

$objectPathString = '/home/brian/sandbox/nugget/objects';
$schemaPathString = '/home/brian/sandbox/nugget/schema';

$objectPath = realpath($objectPathString);
$propertiesPath = realpath($objectPathString . DIRECTORY_SEPARATOR . 'properties');

$directory = new \RecursiveDirectoryIterator($objectPathString, \FilesystemIterator::FOLLOW_SYMLINKS);
$filter = new MyRecursiveFilterIterator($directory);
$iterator = new \RecursiveIteratorIterator($filter);
$objects = array();
foreach ($iterator as $info) {
  $objects[] = $info->getPathname();
}

foreach($objects as $objectFile) {
  
  $schemaPath = str_replace($objectPathString, $schemaPathString, $objectFile);

  //$objectPathString
  echo "Compiling: `$objectFile` to schema: `$schemaPath` \n";
  
  compileObjectSchema($objectFile, $schemaPath);
  
}

function compileObjectSchema($objectFile, $schemaPath) {
  
  $object = json_decode(file_get_contents($objectFile));
  
  $propsObj = new stdClass();
  
  if(is_object($object->properties)) {
    
    foreach($object->properties as $propName => $property) {

      $objectPathString = '/home/brian/sandbox/nugget/objects';
      $schemaPathString = '/home/brian/sandbox/nugget/schema';

      $objectPath = realpath($objectPathString);
      $propertiesPath = realpath($objectPathString . DIRECTORY_SEPARATOR . 'properties');
      
      if(is_object($property)) {
        
          $propObj = $property;
          $property = $propObj->property;
          unset($propObj->property);
            
          $propertyFile = realpath($propertiesPath . DIRECTORY_SEPARATOR . $property . '.json');
          
          if(!empty($propertyFile)) {
            
              $propDefinition = json_decode(file_get_contents($propertyFile));
              
              // Merge the properties object.
              $mergedObj = (object) array_merge((array) $propDefinition, (array) $propObj);

              $propsObj->$propName = $mergedObj;
          }
        
      } else {
          
          $propertyFile = realpath($propertiesPath . DIRECTORY_SEPARATOR . $property . '.json');
          
          if(!empty($propertyFile)) {
              $propDefinition = json_decode(file_get_contents($propertyFile));

              $propsObj->$propName = $propDefinition;
          }
      }

      

      
    }
    
  } else {
    
    foreach($object->properties as $property) {

      $objectPathString = '/home/brian/sandbox/nugget/objects';
      $schemaPathString = '/home/brian/sandbox/nugget/schema';

      $objectPath = realpath($objectPathString);
      $propertiesPath = realpath($objectPathString . DIRECTORY_SEPARATOR . 'properties');

      $propertyFile = realpath($propertiesPath . DIRECTORY_SEPARATOR . $property . '.json');

      if(!empty($propertyFile)) {
          $propDefinition = json_decode(file_get_contents($propertyFile));

          $propsObj->$property = $propDefinition;
      }
    }
  }
  
  $object->properties = $propsObj;
  
  $jsonData = json_encode($object, JSON_PRETTY_PRINT);
  
  file_put_contents($schemaPath, $jsonData);
}

class MyRecursiveFilterIterator extends \RecursiveFilterIterator {

  public function accept() {
    
    $name = $this->current()->getFilename();

    // Skip hidden files and directories.
    if ($name[0] === '.') {
      return FALSE;
    }
    if ($this->isDir()) {
      // Only recurse into intended subdirectories.
      return $name != 'properties';
    }
    else {
      // Only consume files of interest.
      $ext = pathinfo($name, PATHINFO_EXTENSION);
      if($ext === 'json') {
        return true;
      }     
      return false;
    }
  }

}

/**

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($objectPath), RecursiveIteratorIterator::SELF_FIRST);
foreach($objects as $name => $object){
  
  $ext = pathinfo($name, PATHINFO_EXTENSION);
  if($ext === 'json') {
    
    $schemaPath = str_replace($objectPathString, $schemaPathString, $name);
    
    //$objectPathString
    echo "$name > $schemaPath";
    
  }
}
 * 
 */