<?php
use Interop\Container\ContainerInterface;
/**
 * Description of user_management
 *
 * @author srainsdon
 */
class user_management {

    protected $container;
    private $ip;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->set_ip_addr();
    }

    public function login($request, $response, $args) {
        var_dump();
//        if ($request->isPost()) {
//            $username = $request->post('UserName');
//            $password = $request->post('Password');
//            var_dump(array($username, $password));
//        }
//        else {
            return $this->container->renderer->render($response, "login.phtml",
                            ["ip" => $this->ip]);
        //}
    }

    function get_ip_addr() {
        return $this->ip;
    }

    function set_ip_addr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->ip = $ip;
        return $ip;
    }

}
