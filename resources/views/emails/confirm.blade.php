Hola {{$user->name}}
Has cambiado exitosamente tu corre.
Por favor verificala usando el siguiente link:
{{route('verify', $user->verification_token)}}