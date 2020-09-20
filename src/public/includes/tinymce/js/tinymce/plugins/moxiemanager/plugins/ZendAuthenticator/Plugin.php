<?php
/**
 * Use Zend_Auth to validate the logged in user.
 *
 * @author Sysco
 */

// Load Zend bootstrap


class SYSCO_ZendAuthenticator_Plugin implements MOXMAN_Auth_IAuthenticator {
    
    public function authenticate(MOXMAN_Auth_User $user) {
        $config = MOXMAN::getConfig();
        
        // Load environment and session logic
        
        // Check ACL
        
        // return auth result
        return true;
    }
}

MOXMAN::getAuthManager()->add("ZendAuthenticator", new SYSCO_ZendAuthenticator_Plugin());