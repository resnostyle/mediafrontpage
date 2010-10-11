<?php

  function to_readable_size($size) {
    switch (true) {
      case ($size > 1000000000000):
            $size /= 1000000000000;
            $suffix = 'Tb';
      break;
      case ($size > 1000000000):  
            $size /= 1000000000;
            $suffix = 'Gb';
      break;
      case ($size > 1000000):
            $size /= 1000000;
            $suffix = 'Mb';   
      break;
      case ($size > 1000):
            $size /= 1000;
            $suffix = 'Kb';
      break;
      default:
        $suffix = 'b';
    }
    return round($size, 0)." ".$suffix;
  }
  
  function disk_used_space($value) {
    return disk_total_space("$value") - disk_free_space("$value");
  }

  function disk_used_percentage($value) {
    return round(disk_used_space("$value") / disk_total_space("$value") * 100, 2);
  }

?>
