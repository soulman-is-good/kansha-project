<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Soul_man
 */
class CompanyIdentity extends X3_User{
    
    public function __construct($username,$password) {
        $this->username = mysql_real_escape_string($username);
        $tmp = '';
        for($i=0;$i<strlen($password);$i++){
            $tmp.=chr(ord($password[$i])+1);
        }
        
        $this->password = $tmp;
        parent::__construct();
    }
    
    public function recall() {
        if(FALSE!==($params = parent::recall())){
            $this->username = $params['username'];
            $this->password = $params['password'];
            $this->authenticate();
        }
    }
    
    public function  authenticate() {
        $user = Company::newInstance()->table->select('*')->where("`login`='$this->username' AND `password`='$this->password'")->asObject(true);
        if($user == NULL) return false;
        $this->id = $user->id;
        $this->login = $user->login;
        $this->name = $user->title;
        $this->role = 'company';
        $add = $user->getAddress();
        if($add->count()>0)
            $this->email = $add[0]->email;
        $user->lastbeen_at = time();
        return $user->table->save();
    }
    
    
    static public function parseDefaultLanguage($http_accept, $deflang = "ru") {
        if (isset($http_accept) && strlen($http_accept) > 1) {
            # Split possible languages into array
            $x = explode(",", $http_accept);
            foreach ($x as $val) {
                #check for q-value and create associative array. No q-value means 1 by rule
                if (preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i", $val, $matches))
                    $lang[$matches[1]] = (float) $matches[2];
                else
                    $lang[$val] = 1.0;
            }

            #return default language (highest q-value)
            $qval = 0.0;
            foreach ($lang as $key => $value) {
                if ($value > $qval) {
                    $qval = (float) $value;
                    $deflang = $key;
                }
            }
        }
        $deflang = substr($deflang,0,strpos($deflang,'-')-1);
        return strtolower($deflang);
    }
        
}
?>
