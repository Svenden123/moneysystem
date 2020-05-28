Причина: {{$payment['description']}}
На сумму: {{$payment['sum']}}
<form action="/api/{{$payment['uuid']}}/pay" method="post">
    <input type="text" name="card">
    <input type="submit">
</form>
