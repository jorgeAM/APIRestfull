Hola {{$user->name}}
Gracias por crear una cuenta.
Por favor verificala usando el siguiente link:
{{route('verify', $user->verification_token)}}