<?php    
    function user_hash_password($password, $count_log2 = 0) {
        //$salt = 'VrOuWeNpAsSiE.nL';
        
      //if (empty($count_log2)) {
        // Use the standard iteration count.
        //$count_log2 = variable_get('password_count_log2', DRUPAL_HASH_COUNT);
      //}
      return md5($password);//sha1($salt.$password);
    }

    function user_check_password($password, $account) {
        //$salt = 'VrOuWeNpAsSiE.nL';
        
      if (substr($account->pass, 0, 2) == 'U$') {
        // This may be an updated password from user_update_7000(). Such hashes
        // have 'U' added as the first character and need an extra md5().
        $stored_hash = substr($account->pass, 1);
        $password = md5($password);
      }
      else {
        $stored_hash = $account->pass;
      }
      
      $type = substr($stored_hash, 0, 3);
      switch ($type) {
        case '$S$':
          // A normal Drupal 7 password using sha512.
          //$hash = _password_crypt('sha512', $password, $stored_hash);
          break;
        case '$H$':
          // phpBB3 uses "$H$" for the same thing as "$P$".
        case '$P$':
          // A phpass password generated using md5.  This is an
          // imported password or from an earlier Drupal version.
          //$hash = _password_crypt('md5', $password, $stored_hash);
          break;
        default:
            //$hash = sha1($salt.$password);
            $hash = md5($password);
          break;
      }
      return ($hash && $stored_hash == $hash);
    }

    function user_needs_new_hash($account) {
      // Check whether this was an updated password.
      //if ((substr($account->pass, 0, 3) != '$S$') || (strlen($account->pass) != DRUPAL_HASH_LENGTH)) {
        //return TRUE;
      //}
      // Ensure that $count_log2 is within set bounds.
      //$count_log2 = _password_enforce_log2_boundaries(variable_get('password_count_log2', DRUPAL_HASH_COUNT));
      // Check whether the iteration count used differs from the standard number.
      //return (_password_get_count_log2($account->pass) !== $count_log2);
        
      return false;
    }