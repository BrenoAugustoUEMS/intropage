🌟 Plugin de Página Introdutória do Curso (local_intropage)
📄 Descrição
O Plugin de Página Introdutória do Curso adiciona uma página inicial personalizada para os cursos no Moodle.
Essa página exibe informações importantes antes de o usuário acessar o curso, como:

Resumo do curso.
Público-alvo.
Objetivos de Desenvolvimento Sustentável (ODS).
Período de inscrição.
Link para o edital.
Botão dinâmico de acesso/inscrição.
✨ Principais Funcionalidades
📃 Página Introdutória:

Apresenta informações detalhadas do curso, como:
Nome e resumo do curso.
Período de inscrição (com base no método de autoinscrição).
Categoria do curso.
Objetivos de Desenvolvimento Sustentável (ODS).
Link para edital, se configurado.
Botão dinâmico com três estados:
Inscreva-se: Se o usuário não está inscrito e a autoinscrição está habilitada.
Acesse: Se o usuário já está inscrito ou tem permissões de gerente/administrador.
Inscrição indisponível: Se a autoinscrição não está configurada.
🔧 Campos Personalizados:

Utiliza campos personalizados para exibir informações adicionais:
ods: Lista de ODS aplicáveis ao curso.
edital_url: Link para o edital do curso.
target (opcional): Público-alvo.
actions (opcional): Ações contempladas no curso.
🎨 Fácil Personalização:

Suporte a templates Mustache para personalizar a aparência da página introdutória.

⚙️ Configuração
Passo 1: Criar os Campos Personalizados
Antes de usar o plugin, configure os seguintes campos personalizados no Moodle:

ods (Texto):
Lista de números de 1 a 17, separados por vírgulas, representando os ODS.
Exemplo: 1,4,13.

edital_url (URL):
Insira o link para o edital do curso.
Exemplo: https://uems.br/edital-curso-x.

target (Texto - opcional):
Descreve o público-alvo do curso.
Exemplo: Estudantes, professores, técnicos.

actions (Texto - opcional):
Descreve as ações contempladas pelo curso.
Exemplo: Aulas síncronas, entrega de certificado.

🚀 Como Funciona
Acessar a Página Introdutória:

Acesse a página do curso ou use o botão direto no card do curso para acessar a página introdutória.
Comportamento do Botão:

Inscreva-se: Aparece se o usuário não está inscrito e a autoinscrição está habilitada.
Acesse: Aparece se o usuário já está inscrito.
Inscrição indisponível: Aparece se a autoinscrição não está configurada.
ODS:

Exibe os selos correspondentes aos números configurados no campo personalizado ods.
Selos são armazenados no diretório pix/sdg/.
🧪 Testando o Plugin

1. Instalação
   Coloque a pasta intropage no diretório local/ do Moodle.
   Acesse:
   Administração do site > Notificações
   para concluir a instalação.
2. Cenários para Testar
   Certifique-se de testar os seguintes cenários:

Cenário 1: Página Introdutória
Verifique se a página introdutória é exibida corretamente com os seguintes elementos:
Nome e resumo do curso.
ODS configurados.
Link do edital.
Público-alvo (opcional).
Cenário 2: Botão Dinâmico
Inscreva-se: Aparece para usuários não inscritos.
Acesse: Aparece para usuários inscritos.
Indisponível: Aparece quando a autoinscrição não está configurada.
Cenário 3: Campos Personalizados
Teste os valores inseridos nos campos personalizados:
ODS exibem corretamente os selos.
Link do edital direciona para o URL correto.
📌 Notas Importantes
Customização no Tema:

Para exibir o link da página introdutória nos cards de cursos, foi necessário alterar os templates do tema ativo.
Templates modificados: coursecard.mustache no tema almondb.
Redirecionamento Automático:

Atualmente, o redirecionamento automático ao acessar o curso está desabilitado.
O comportamento será implementado em versões futuras.
🛠️ Possíveis Melhorias Futuras
Redirecionamento automático ao acessar o curso.
Configuração centralizada no painel administrativo.
Suporte completo a múltiplos idiomas.
Testes automatizados.
👨‍💻 Créditos
Desenvolvedor: Breno Augusto
Contato: brenoaugusto@uems.br
Licença: GNU GPL v3
