<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="conteudos.css" />
  <title>Conteudos - Sci3D</title>
  <style>
    /* Segment base styles */
    .segment {
      width: 150px;
      cursor: pointer;
      transition: all 0.3s ease;
      overflow: hidden;
      border: 1px solid #ccc;
      padding: 10px;
      background: white;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 1;
    }
    .segment img {
      width: 100%;
      height: auto;
      display: block;
      border-radius: 4px;
    }
    /* Título no estado normal: limitar a uma linha com reticências */
    .segment h3 {
      margin: 10px 0 0 0;
      text-align: center;
      font-size: 1.1rem;
      white-space: nowrap;       /* Não quebra linha */
      overflow: hidden;          /* Esconde o excesso */
      text-overflow: ellipsis;   /* Mostra "..." no final */
      max-width: 140px;          /* Limita a largura para caber no segmento */
    }
    /* No estado expandido, título completo, sem corte */
    .segment.expanded h3 {
      white-space: normal;
      overflow: visible;
      text-overflow: clip;
      max-width: none;
      text-align: left;
      font-size: 1rem;
      margin: 0 0 10px 0;
    }

    /* Expanded styles with overlay */
    .segment.expanded {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 600px;
      max-width: 90vw;
      max-height: 90vh;
      background: #f9f9f9;
      flex-direction: row;
      gap: 20px;
      align-items: flex-start;
      cursor: default;
      padding: 20px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
      border-radius: 10px;
      overflow-y: auto;
      z-index: 1000;
    }
    .segment.expanded img {
      width: 250px;
      flex-shrink: 0;
      border-radius: 6px;
    }
    .segment .content-details {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }
    .segment .content-details h3 {
      margin: 0 0 10px 0;
      font-size: 1rem;
      text-align: left;
    }
    .segment .description {
      min-height: 150px; /* aumentei de 60px para 150px */
      margin-bottom: 10px;
      font-size: 1rem;
      color: #333;
      white-space: pre-wrap;
      overflow-y: auto; /* permite scroll se o texto for muito grande */
      max-height: 300px; /* limite máximo para não estourar */
    }

    /* Botão YouTube */
    .segment .youtube-button {
      display: inline-block;
      padding: 8px 16px;
      background-color: #c4302b;
      color: white;
      border: none;
      border-radius: 4px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .segment .youtube-button:hover {
      background-color: #a52720;
    }

    /* Overlay background */
    #overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0,0,0,0.5);
      z-index: 900;
      display: none;
    }

    /* Container styles */
    .segments-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      padding: 20px;
      justify-content: center;
    }
  </style>
</head>
<body>
  <!-- Menu de navegação -->
  <nav
    class="menu"
    style="display: flex; justify-content: space-between; align-items: center; padding: 10px;"
  >
    <!-- Parte esquerda (imagem + links) -->
    <div style="display: flex; align-items: center; gap: 20px;">
      <!-- Imagem clicável -->
      <a href="home.php">
        <img
          src="../Login/SC.png"
          alt="Logo"
          style="height: 60px; cursor: pointer;"
        />
      </a>

      <!-- Links -->
      <ul
        style="
          list-style: none;
          display: flex;
          gap: 20px;
          margin: 0;
          padding: 0;
        "
      >
        <li><a href="home.php">Conteúdos</a></li>
        <li><a href="../home/feedback/contatos.php">Feedback</a></li><!--MUDEI APENAS ISSO AQUI PARA DIRECIONAR -->
      </ul>
    </div>

    <!-- Parte direita (busca + perfil) -->
    <div style="display: flex; align-items: center; gap: 15px;">
      <!-- Substitua seu input de busca por este, adicionando id="searchInput" -->
      <input
        type="text"
        id="searchInput"
        placeholder="Buscar..."
        style="
          padding: 6px 12px;
          border: 1px solid #ccc;
          border-radius: 20px;
          outline: none;
        "
      />

      <!-- Ícone do perfil -->
      <img
        src="https://media.istockphoto.com/id/1495088043/pt/vetorial/user-profile-icon-avatar-or-person-icon-profile-picture-portrait-symbol-default-portrait.jpg?s=612x612&w=0&k=20&c=S7d8ImMSfoLBMCaEJOffTVua003OAl2xUnzOsuKIwek="
        alt="Perfil"
        class="perfil-img"
        onclick="window.location.href='perfil.php'"
        style="cursor: pointer; height: 40px; width: 40px; border-radius: 50%"
      />
    </div>
  </nav>

  <!-- Overlay para fundo escurecido -->
  <div id="overlay"></div>

  <!-- Conteúdo principal -->
  <div class="content-wrapper">
    <div class="segments-container" id="segmentsContainer">
      <!-- Segments will be generated here by JS -->
    </div>
  </div>

  <script>
    // Dados dos conteúdos para replicar facilmente
    const contents = [
      {
        img: "https://s4.static.brasilescola.uol.com.br/be/2022/10/modelos-atomicos.jpg",
        alt: "Imagem 1",
        title: "Modelos Atomicos",
        description: "Modelos atômicos são representações teóricas que tentam explicar a estrutura e o comportamento dos átomos, ou seja, como os átomos são formados e como seus componentes (como os elétrons, prótons e nêutrons) se organizam. Cada modelo tenta resolver questões que os modelos anteriores não conseguiam, à medida que novas descobertas eram feitas",
        youtube: "https://youtu.be/5-fa4IKp5bU?si=hYeTJb6IUDj0Nv7",
      },
      {
        img: "https://www.infoescola.com/wp-content/uploads/2007/02/estados-materia.jpg",
        alt: "Imagem 2",
        title: "	Introdução a Química, Matéria e Energia. Classificação de Matéria.",
        description: "A Química é a ciência que estuda a composição, as transformações e as propriedades da matéria, além das formas de energia envolvidas nesses processos. Logo na introdução, o objetivo é compreender conceitos fundamentais que servirão de base para todo o estudo da disciplina.",
        youtube: "https://www.youtube.com/watch?v=ANnkstfG6nE",
      },
      {
        img: "https://www.materialparalaboratorio.com.br/imagens/frasco-quimico.jpg",
        alt: "Imagem 3",
        title: "Sistemas de Medidas e Unidades. Volume, Temperatura, Densidade, Pressão Atmosférica",
        description: "Os sistemas de medidas e unidades são fundamentais para a ciência e o cotidiano, pois permitem padronizar e comparar resultados de experimentos, cálculos e fenômenos. O mais utilizado é o Sistema Internacional de Unidades (SI), que define padrões universais para grandezas físicas.",
        youtube: "https://www.youtube.com/watch?v=U_BemByzivk",
      },
      {
        img: "https://saci.org.br/wp-content/uploads/2019/05/separacao-de-misturas.jpg",
        alt: "Imagem 4",
        title: "Separação de Misturas",
        description: "No cotidiano e na ciência, é comum encontrarmos misturas, ou seja, combinações de duas ou mais substâncias. Para obter as substâncias de forma isolada, utilizamos os processos de separação de misturas, que variam de acordo com o tipo de mistura (homogênea ou heterogênea) e as propriedades físicas de seus componentes.",
        youtube: "https://www.youtube.com/watch?v=YRGyXswza8g",
      },
      {
        img: "https://blogdoenem.com.br/wp-content/uploads/2014/02/diagrama-de-linus-pauling-destacada.jpg",
        alt: "",
        title: "Diagrama de Linus Pauling",
        description: "A média atômica ponderada e a distribuição eletrônica são conceitos fundamentais em Química. A média atômica ponderada é utilizada para calcular a massa de um elemento químico que possui isótopos, ou seja, átomos do mesmo elemento com diferentes massas. Esse cálculo considera a abundância relativa de cada isótopo na natureza, resultando em um valor que representa a massa atômica do elemento na Tabela Periódica. Um exemplo é o cloro, que possui os isótopos Cl-35 e Cl-37, e cuja massa atômica média é de aproximadamente 35,5 unidades de massa atômica. Já a distribuição eletrônica trata da maneira como os elétrons se organizam ao redor do núcleo do átomo.",
        youtube: "https://youtu.be/qqcIOzOCO4o",
      },
      {
        img: "https://i.ytimg.com/vi/eiJJstzniDs/maxresdefault.jpg", 
        alt: "Imagem 6",
        title: "Estequiometria Introdução-Parte 1",
        description: "A estequiometria é a área da Química que estuda as relações quantitativas entre reagentes e produtos em uma reação química. Dois conceitos fundamentais nesse estudo são o mol e a massa molar. O mol é a unidade que mede a quantidade de substância, correspondendo a 6,022×10^23 partículas (átomos, moléculas ou íons), também conhecido como número de Avogadro. A massa molar é a massa de um mol de uma substância, expressa em gramas por mol (g/mol), e é obtida somando as massas atômicas dos elementos que compõem a molécula. Com esses conceitos, é possível calcular quantos mols de reagentes são necessários para produzir uma certa quantidade de produto ou vice-versa, facilitando a resolução de problemas de proporções químicas e garantindo o entendimento das relações quantitativas em reações químicas.",
        youtube: "https://www.youtube.com/watch?v=SnSgzpUku2o",
      },
      {
        img: "https://blogdoenem.com.br/wp-content/uploads/2014/09/esquiometria-07.jpg",
        alt: "Imagem 6",
        title: "Estequiometria Introdução-Parte 2",
        description: "Na estequiometria, podemos relacionar a quantidade de moléculas e o volume dos gases por meio do conceito de mol. Um mol de qualquer substância contém 6,02 × 10²³ moléculas (Constante de Avogadro). Para os gases, em condições normais de temperatura e pressão (CNTP), esse mesmo 1 mol corresponde a 22,4 L de volume. Assim, é possível converter entre volume de gás e número de moléculas: sabendo o volume em litros, divide-se por 22,4 L para achar os mols e, em seguida, multiplica-se pela Constante de Avogadro para obter o número de moléculas.",
        youtube: "https://www.youtube.com/watch?v=Zpn2LuLlS0E",
      },
      {
        img: "https://static.preparaenem.com/conteudo_legenda/1a6120066b7bb408963ceca0b10611e1.jpg",
        alt: "Imagem 6",
        title: "Leis Ponderais - Lavoisier e Proust",
        description: "Lei da Conservação da Massa (Lavoisier, 1789): “Na natureza, nada se perde, nada se cria, tudo se transforma.” Ou seja, em uma reação química, a massa total dos reagentes é sempre igual à massa total dos produtos. Isso mostrou que a matéria não desaparece, apenas se rearranja. Lei das Proporções Definidas (Proust, 1799): Em um mesmo composto químico, os elementos estão sempre combinados em proporções fixas de massa. Por exemplo, a água (H₂O) é formada por hidrogênio e oxigênio sempre na razão de 1:8 em massa, independentemente da amostra ou da origem.",
        youtube: "https://www.youtube.com/watch?v=Xw07tP-3Y84",
      },
      {
        img: "https://s1.static.brasilescola.uol.com.br/be/2020/08/gas-ideal.jpg",
        alt: "Imagem 6",
        title: "Gases. Equação de Clapeyron: fórmula, aplicação, exercícios. Leis de Boyle, Gay-Lussac e Charles.	",
        description: "O estudo dos gases está baseado na compreensão da relação entre pressão, volume, temperatura e quantidade de matéria. As Leis dos Gases descrevem transformações específicas: a Lei de Boyle mostra que, em temperatura constante, a pressão e o volume são inversamente proporcionais; a Lei de Charles estabelece que, em pressão constante, o volume é diretamente proporcional à temperatura absoluta; já a Lei de Gay-Lussac demonstra que, em volume constante, a pressão é diretamente proporcional à temperatura absoluta. Essas leis foram unificadas na Equação de Clapeyron, ou equação do gás ideal, expressa por PV = nRT, que relaciona todas as variáveis em uma única fórmula. Esse modelo permite prever o comportamento dos gases em diferentes condições, além de ser aplicado em cálculos estequiométricos e em fenômenos do cotidiano, como o funcionamento de motores, balões e até mesmo o processo da respiração.",
        youtube: "https://youtu.be/-mjT-btLJYM",
      },
      {
        img: "https://static.todamateria.com.br/upload/di/ag/diagramapvdociclodecarnot-0-cke.jpg",
        alt: "Imagem 6",
        title: "Gases com Análise de gráficos - Isotermas, Isobáricas e Isocóricas. Introdução à adiabáticas",
        description: "O estudo das transformações gasosas aborda como os gases se comportam quando pressão, volume e temperatura variam de maneiras específicas. Nas transformações isotérmicas, a temperatura permanece constante, fazendo com que a pressão e o volume sejam inversamente proporcionais; nas isobáricas, a pressão se mantém constante, e o volume varia de forma diretamente proporcional à temperatura; já nas isocóricas, o volume não muda, e a pressão se altera proporcionalmente à temperatura. Além dessas, a transformação adiabática ocorre sem troca de calor com o meio externo, fazendo com que mudanças na pressão e no volume alterem diretamente a temperatura do gás. Esse conteúdo é essencial para compreender gráficos de pressão versus volume (PV) e entender os princípios da termodinâmica aplicada aos gases, sendo útil em cálculos, experimentos e aplicações práticas como motores e sistemas de aquecimento e refrigeração.",
        youtube: "https://www.youtube.com/watch?v=CLSiC92Ew04",
      },
      {
        img: "https://static.todamateria.com.br/upload/ac/id/acidos-e-bases-og.jpg",
        alt: "Imagem 6",
        title: "Ácidos e Bases",
        description: "O estudo de ácidos e bases envolve a compreensão de substâncias químicas que apresentam propriedades opostas: os ácidos liberam íons H⁺ em solução aquosa e geralmente possuem sabor azedo, enquanto as bases liberam íons OH⁻ e apresentam sabor amargo e toque escorregadio. Existem diferentes teorias que explicam esses comportamentos: a teoria de Arrhenius define ácidos e bases pela presença de H⁺ e OH⁻; a teoria de Brønsted-Lowry considera ácidos como doadores de prótons e bases como receptores de prótons; e a teoria de Lewis define ácidos como receptores de pares de elétrons e bases como doadores. Ácidos e bases podem ser fortes ou fracos, dependendo da sua capacidade de ionização, e participam de reações chamadas de neutralização, formando sal e água. Esse conteúdo é fundamental para entender reações químicas, equilíbrio de soluções e aplicações práticas em laboratórios, indústria e vida cotidiana.",
        youtube: "https://www.youtube.com/watch?v=83-CquMqxD8",
      },
      {
        img: "https://static.manualdaquimica.com/conteudo/images/o-criterio-mais-importante-classificacao-dos-acidos-quanto-sua-forca-546baf2d0796a.jpg",
        alt: "Imagem 6",
        title: "Classificações dos ácidos",
        description: "A classificação dos ácidos envolve diferentes critérios que ajudam a entender suas propriedades e comportamentos em reações químicas. Quanto à composição, os ácidos podem ser: hidrácidos, formados por hidrogênio ligado a um elemento não metálico (como HCl, H₂S), e oxiácidos, que contêm hidrogênio, oxigênio e outro elemento (como H₂SO₄, HNO₃). Quanto ao número de hidrogênios ionizáveis, podem ser monopróticos (liberam 1 H⁺), dipróticos (liberam 2 H⁺) ou tripróticos (liberam 3 H⁺). Outro critério é a força ácida, que indica o grau de ionização em solução aquosa: ácidos fortes ionizam-se completamente (como HCl), enquanto ácidos fracos ionizam-se parcialmente (como CH₃COOH). Essa classificação é fundamental para entender reações de neutralização, equilíbrio ácido-base e o comportamento químico dos ácidos em diferentes contextos laboratoriais e industriais",
        youtube: "https://www.youtube.com/watch?v=_Da_NDH3yvA&pp=ygUdQ2xhc3NpZmljYcOnw7VlcyBkb3Mgw6FjaWRvcyA%3D",
      },
      {
        img: "https://static.todamateria.com.br/upload/no/me/nomenclatura-dos-acidos-og.jpg",
        alt: "Imagem 6",
        title: "Nomenclatura dos Ácidos",
        description: "A nomenclatura de ácidos é o conjunto de regras utilizadas para dar nomes corretos aos ácidos, de forma que sua composição química fique clara. Para hidrácidos (ácidos formados por hidrogênio e um não metal), o nome se forma com o prefixo “ácido”, seguido do nome do elemento com a terminação “-ídrico” (por exemplo, HCl é ácido clorídrico). Para oxiácidos (ácidos que contêm hidrogênio, oxigênio e outro elemento), a nomenclatura depende do número de oxigênios: se o elemento central tem mais oxigênios, o sufixo é “-ico” (H₂SO₄: ácido sulfúrico); se tem menos oxigênios, o sufixo é “-oso” (H₂SO₃: ácido sulfuroso). Essa nomenclatura permite identificar facilmente a composição dos ácidos, facilitando a comunicação científica e o estudo de reações químicas, como neutralizações e equilíbrios ácido-base.",
        youtube: "https://www.youtube.com/watch?v=Q-CwtK3yHIg&pp=ygUYbm9tZW5jbGF0dXJhIGRvcyDDoWNpZG9z",
      },
      {
        img: "https://static.todamateria.com.br/upload/mi/st/misturassolucaoquimica-cke.jpg", 
        alt: "",
        title: "Conceitos de soluções, coloide e dispersão. Início de soluções aquosas (Concentração).",
        description: "O estudo de soluções, coloides e dispersões está ligado à forma como diferentes substâncias se misturam. As dispersões são classificadas de acordo com o tamanho das partículas dispersas: nas soluções, as partículas são muito pequenas e ficam distribuídas de forma homogênea, sem poderem ser vistas a olho nu nem separadas por filtração comum (como a água com sal). Já os coloides possuem partículas maiores que as das soluções, mas ainda assim não se sedimentam facilmente, apresentando efeitos visuais característicos como o efeito Tyndall (exemplo: leite, gelatina). As suspensões têm partículas grandes, que tendem a se depositar com o tempo (como areia em água). Quando se trata de soluções aquosas, o solvente é a água, e é importante estudar as formas de medir a quantidade de soluto dissolvido, o que se chama concentração. Essa pode ser expressa de várias maneiras, como concentração comum, molaridade, porcentagem em massa, entre outras. Esse conteúdo é essencial para entender processos químicos no cotidiano, na indústria e em laboratórios, já que a maioria das reações químicas ocorre em meio aquoso.",
        youtube: "https://www.youtube.com/watch?v=qgMYuda63mI",
      },
      {
        img: "https://files.cursoenemgratuito.com.br/uploads/2020/06/solu%C3%A7%C3%B5es-tipos-de-dispers%C3%B5es.jpg", 
        alt: "",
        title: "Misturas Homogêneas e Heterogêneas, Soluções verdadeiras, coloides, suspensões. Soluções aquosas I",
        description: "As misturas podem ser classificadas em homogêneas e heterogêneas. As homogêneas apresentam aspecto uniforme em toda a sua extensão e são chamadas de soluções verdadeiras, como ocorre quando se dissolve sal ou açúcar em água. Já as heterogêneas apresentam mais de uma fase visível, como no caso da água e do óleo. Dentro das misturas heterogêneas existem diferentes tipos de dispersões, que variam conforme o tamanho das partículas. Nas soluções verdadeiras, as partículas são muito pequenas, invisíveis a olho nu e não podem ser separadas por filtração simples. Nos coloides, as partículas têm tamanho intermediário, não sedimentam facilmente e podem apresentar o efeito Tyndall, como no leite ou na gelatina. Já nas suspensões, as partículas são grandes e tendem a se depositar com o tempo, como acontece com a água misturada à areia. Nas soluções aquosas, em que a água atua como solvente, a quantidade de soluto dissolvido é expressa pela concentração, que pode ser medida de diferentes formas, como molaridade ou porcentagem. Esse estudo é fundamental porque muitas reações químicas do cotidiano e da indústria acontecem em meio aquoso.",
        youtube: "https://www.youtube.com/watch?v=7CZeKEZLW7A",
      },
    ];

    const container = document.getElementById("segmentsContainer");
    const overlay = document.getElementById("overlay");

    // Função para criar um segmento
    function createSegment(content) {
      const segment = document.createElement("div");
      segment.classList.add("segment");

      // Imagem
      const img = document.createElement("img");
      img.src = content.img;
      img.alt = content.alt || "";
      segment.appendChild(img);

      // Container para texto e detalhes (para expanded view)
      const contentDetails = document.createElement("div");
      contentDetails.classList.add("content-details");

      // Título
      const h3 = document.createElement("h3");
      h3.textContent = content.title || "";
      contentDetails.appendChild(h3);

      // Descrição (inicialmente hidden, shown on expand)
      const description = document.createElement("p");
      description.classList.add("description");
      description.textContent = content.description || "";
      contentDetails.appendChild(description);

      // Botão YouTube
      const youtubeButton = document.createElement("button");
      youtubeButton.classList.add("youtube-button");
      if (content.youtube) {
        youtubeButton.textContent = "Ficou interessado?";
        youtubeButton.addEventListener("click", (e) => {
          e.stopPropagation(); // para não fechar o segmento ao clicar no botão
          window.open(content.youtube, "_blank");
        });
      } else {
        youtubeButton.style.display = "none";
      }
      contentDetails.appendChild(youtubeButton);

      segment.appendChild(contentDetails);

      // Inicialmente hide description and button by CSS (only show on expand)
      description.style.display = "none";
      youtubeButton.style.display = "none";

      // Clique para expandir/recolher
      segment.addEventListener("click", (event) => {
        event.stopPropagation(); // Evita que o clique propague para o overlay

        const isExpanded = segment.classList.contains("expanded");

        // Fecha todos os outros segmentos abertos
        document.querySelectorAll(".segment.expanded").forEach((seg) => {
          if (seg !== segment) {
            seg.classList.remove("expanded");
            seg.querySelector(".description").style.display = "none";
            seg.querySelector(".youtube-button").style.display = "none";
          }
        });

        if (!isExpanded) {
          segment.classList.add("expanded");
          description.style.display = "block";
          youtubeButton.style.display = content.youtube ? "inline-block" : "none";
          overlay.style.display = "block";
          // Desabilitar scroll do body enquanto aberto
          document.body.style.overflow = "hidden";
        } else {
          segment.classList.remove("expanded");
          description.style.display = "none";
          youtubeButton.style.display = "none";
          overlay.style.display = "none";
          document.body.style.overflow = "";
        }
      });

      return segment;
    }

    // Fecha o segmento expandido ao clicar no overlay
    overlay.addEventListener("click", () => {
      const expandedSegment = document.querySelector(".segment.expanded");
      if (expandedSegment) {
        expandedSegment.classList.remove("expanded");
        expandedSegment.querySelector(".description").style.display = "none";
        expandedSegment.querySelector(".youtube-button").style.display = "none";
      }
      overlay.style.display = "none";
      document.body.style.overflow = "";
    });

    // Renderiza todos os segmentos
    function renderSegments(contentsToRender) {
      container.innerHTML = ""; // limpa container
      contentsToRender.forEach((content) => {
        const segment = createSegment(content);
        container.appendChild(segment);
      });
    }

    renderSegments(contents);

    const searchInput = document.getElementById("searchInput");

    // Evento para filtro de busca
    searchInput.addEventListener("input", () => {
      const searchTerm = searchInput.value.trim().toLowerCase();

      if (searchTerm === "") {
        // Se vazio, mostra todos
        renderSegments(contents);
      } else {
        // Filtra conteúdos cujo título contenha o termo buscado
        const filtered = contents.filter((content) =>
          content.title.toLowerCase().includes(searchTerm)
        );
        renderSegments(filtered);
      }
    });
  </script>
</body>
</html>
