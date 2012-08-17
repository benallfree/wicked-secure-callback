<?

class SecureCallbackMixin extends Mixin
{
  static $__prefix = "sc";
  
  static function init()
  {
    parent::init();

    W::register_action('secure_callback_before_validate', function($sc) {
      if (!$sc->token)
      {
        $sc->token = md5(json_encode($sc->data) . uniqid() . microtime(true));
      }
      return $sc;
    });
    
    W::register_action('secure_callback_serialize', function($sc) {
      $sc->data = json_encode($sc->data);
      return $sc;
    });
    
    W::register_action('secure_callback_unserialize', function($sc) {
      $sc->data = json_decode($sc->data,true);
      return $sc;
    });
    
    SecureCallback::add_function('expire', function($sc) {
      $sc->used_at = time();
      $config = W::module('sc');
      if(!$config['is_test_mode']) $sc->save();
      if(!$sc->is_valid)
      {
        W::error("Failed to expire secure callback {$sc->id}", $sc->errors);
      }
    });    
  }
  
  static function create_url($func_name)
  {
    $args = func_get_args();
    array_shift($args);
    $args = array(
      'name'=>$func_name,
      'args'=>$args,
    );
    $callback = SecureCallback::create( array(
      'attributes'=>array(
        'data'=>$args
      )
    ));
    $url = "/sc?a={$callback->token}";
    return $url;
  }
  
  static function get_callback_data($token)
  {
    $cb = SecureCallback::find_by_token($token);
    if ($cb) return $cb->args;
    return null;
  }
  
  static function expire_callback($token)
  {
    $cb = SecureCallback::find_by_token($token);
    if($cb) $cb->expire();
  }  
}