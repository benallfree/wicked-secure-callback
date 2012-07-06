<?


W::register_filter('secure_callback_before_validate', function($sc) {
  if (!$sc->token)
  {
    $sc->token = md5(json_encode($sc->data) . uniqid() . microtime() . session_id());
  }
  return $sc;
});

W::register_filter('secure_callback_serialize', function($sc) {
  $sc->data = json_encode($sc->data);
  return $sc;
})

W::register_filter('secure_callback_unserialize', function($sc) {
  $sc->data = json_decode($sc->data,true);
  return $sc;
});

SecureCallback::add_function('expire', function($sc) {
  if(!$sc->expires_at) return;
  $sc->used_at = time();
  $sc->save();
});