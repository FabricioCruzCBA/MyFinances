@component('mail::message')
<h1>{{$mail->h1}}</h1>
<p>{{$mail->p}}</p>
<p>{{$mail->body}}</p>
@component('mail::button',['url'=>$mail->url])
{{$mail->button}}
@endcomponent
@endcomponent