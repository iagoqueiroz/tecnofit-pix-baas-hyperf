# Saque Processado com Sucesso.<br/><br/>

Olá **{{ $userName }}**,<br/>
Seu saque de código **{{ $withdrawId }}** foi processado com sucesso!<br/>

Valor solicitado: R$ {{ number_format($amount, 2, ',', '.') }}<br/>
Chave Pix: {{ $pixKey }}<br/>
Data do processamento: {{ date('d/m/Y H:i', strtotime($scheduled_for)) }}.<br/><br/>

Agradecemos por usar nossa plataforma!<br/>
Se você não reconhece essa transação, entre em contato imediatamente.<br/><br/>

Atenciosamente,<br/>
**Equipe TecnoFIT**<br/>