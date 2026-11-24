# Portal Expresso RN

Portal de notícias do Rio Grande do Norte em WordPress e Elementor. A página inicial reúne manchetes, editorias e um feed cronológico. A primeira visita usa o tema claro. O botão ◐ permite escolher o modo escuro e memoriza a escolha neste navegador.

## Rodar no computador

Instale o Node.js 22.13 ou mais recente e abra um terminal na pasta do projeto.

```sh
npm ci
npm run check
npm start
```

Na primeira execução, mantenha uma conexão com a internet para o Playground preparar o WordPress. Abra o painel uma vez para concluir a configuração editorial e depois visite a capa. O primeiro carregamento gera as miniaturas das fotos e pode levar alguns minutos. Para encerrar, pressione Ctrl+C. [Painel local](http://127.0.0.1:8080/wp-admin/) · [Capa local](http://127.0.0.1:8080/)

## Conteúdo e edição

O repositório inclui o tema do portal, o Elementor, os plugins de configuração e uma edição inicial com 11 matérias. As fotos originais dessa edição, os créditos e as licenças individuais estão em `site/wp-content/mu-plugins/edicao/`. O WordPress cria uma biblioteca de mídia e um banco locais ao iniciar uma cópia nova.

Em **Posts**, edite os textos e selecione a imagem destacada que aparece na matéria e nas chamadas. Em **Páginas → Início → Editar com Elementor**, reorganize os blocos da capa.

O Git guarda o código e a edição inicial. As próximas alterações feitas no painel ficam no banco e na biblioteca de mídia daquela instalação. Para levá-las a outra hospedagem, migre também o banco e os uploads.

## Colocar no GitHub

No GitHub Desktop, use **File → Add Local Repository**, selecione esta pasta e clique em **Publish repository**. Escolha a visibilidade antes de confirmar.

O ambiente do Playground oferece acesso administrativo automático para desenvolvimento local. Para publicar o portal na internet, use hospedagem WordPress com PHP e banco de dados próprios. GitHub Pages oferece hospedagem estática.
