# 🌟 Plugin de Página Introdutória do Curso (local_intropage)

## 📄 Descrição

O **Plugin de Página Introdutória do Curso** adiciona uma página inicial personalizada para os cursos no Moodle.  
Essa página exibe informações importantes antes de o usuário acessar o curso.

---

## Principais Funcionalidades

**📃 Página Introdutória:**

- Apresenta informações detalhadas do curso:
  - Nome e resumo.
  - Período de inscrição.
  - Categoria.
  - Objetivos de Desenvolvimento Sustentável (ODS).
- Botões dinâmicos com estados:
  - **Edital**
  - **Edital Indisponível**
  - **Inscreva-se**
  - **Acesse**
  - **Indisponível**

---

## ⚙️ Configuração

**Criar os Campos Personalizados nos Cursos**

- **ods** (Texto Curto)
- **edital_url** (Texto Curto)
- **target** (Texto Curto)
- **actions** (Texto Curto)

  1.  **ODS**

      - **Nome do Campo:** Objetivos de Desenvolvimento Sustentável (ODS)
      - **Nome curto:** ods
      - **Tipo de campo:** Texto curto
      - **Descrição:**
        Insira números representando os ODS, separados por vírgulas (exemplo: 1,4,13).
        Cada número corresponde a um Objetivo de Desenvolvimento Sustentável. Os números válidos são de 1 a 17.
      - **Obrigatório:** Opcional (deixe desmarcado se o curso não tiver ODS definidos).
      - **Visibilidade:** Marque como visível para todos.
      - **Exemplo de valor:** 3,8,11

  2.  **Edital URL**

      - **Nome do Campo:** Link do Edital
      - **Nome curto:** edital_url
      - **Tipo de campo:** Texto curto
      - **Descrição:**
        Insira o link para o edital do curso.
        Deve ser uma URL válida, começando com http:// ou https://.
      - **Obrigatório:** Opcional (deixe desmarcado se o curso não tiver edital).
      - **Visibilidade:** Marque como visível para todos.
      - **Exemplo de valor:** https://www.exemplo.com/edital

  3.  **Público-Alvo**

      - **Nome do Campo:** Público-Alvo
      - **Nome curto:** target
      - **Tipo de campo:** Texto curto
      - **Descrição:**
        Insira o público-alvo do curso (ex.: "Professores, Estudantes, Técnicos").
      - **Obrigatório:** Opcional.
      - **Visibilidade:** Marque como visível para todos.
      - **Exemplo de valor:** Estudantes de graduação e pós-graduação

  4.  **Ações Contempladas**
      - **Nome do Campo:** Ações Contempladas
      - **Nome curto:** actions
      - **Tipo de campo:** Texto curto
      - **Descrição:**
        Descreva brevemente as ações contempladas no curso.
        Use uma lista separada por vírgulas (ex.: "Palestras, Workshops, Certificação").
      - **Obrigatório:** Opcional.
      - **Visibilidade:** Marque como visível para todos.
      - **Exemplo de valor:** Palestras, Workshops, Sessões práticas

**Customização no Tema:**

- Para o redirecionamento funcionar foi necessário sobrescrever um template do core.

- Copie o arquivo localizado em `course/templates/coursecard.mustache`

- Cole o arquivo em `SeuTema/templates/core_course/coursecard.mustache`

- Caso o tema utilizado ainda não tenha esses diretórios, crie.

- Abra o arquivo que foi colado e edite:

  - o que era:
    `href="{{viewurl}}"`

  - Em pelo menos 2 trechos, terá que ser:
    `href="/local/intropage/index.php?courseid={{id}}"`

---

## 📌 Implementações Futuras

1. **Campos nativos do plugin**

   - Tabela do banco de dados própria.

2. ## **Outros campos**

---

## 👨‍💻 Créditos

- **Desenvolvedor**: Breno Augusto
- **Licença**: [GNU GPL v3](http://www.gnu.org/copyleft/gpl.html)
