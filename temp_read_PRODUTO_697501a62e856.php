
<div class="card mb-3">
  <div class="card-header">
    <i class="bi bi-funnel me-2"></i>
    Filtros
  </div>
  <div class="card-body">
    <form method="GET">
      <input type="hidden" name="comum_id" value="1">
      <input type="hidden" name="comum_id" value="1">

      <!-- Campo principal de busca por DESCRIO -->
      <div class="mb-3">
        <label for="filtro_complemento" class="form-label">
          <i class="bi bi-search me-1"></i>
          Pesquisar por Descrio
        </label>
        <input type="text" id="filtro_complemento" name="filtro_complemento" class="form-control" value="" placeholder="Digite para buscar...">
      </div>

      <!-- Filtros Avanados recolhveis -->
      <div class="accordion" id="filtrosAvancados">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiltros">
              <i class="bi bi-sliders me-2"></i>
              Filtros Avanados
            </button>
          </h2>
          <div id="collapseFiltros" class="accordion-collapse collapse" data-bs-parent="#filtrosAvancados">
            <div class="accordion-body">
              <div class="mb-3">
                <label for="pesquisa_id" class="form-label">ID</label>
                <input type="number" id="pesquisa_id" name="pesquisa_id" class="form-control" value="" placeholder="Digite o ID">
              </div>

              <div class="mb-3">
                <label for="filtro_tipo_ben" class="form-label">Tipos de Bens</label>
                <select id="filtro_tipo_ben" name="filtro_tipo_ben" class="form-select">
                  <option value="">Todos</option>
                                      <option value="6" >
                      1 - BANCO DE MADEIRA/GENUFLEXORIO                    </option>
                                      <option value="47" >
                      2 - TRIBUNA/CRIADO MUDO                    </option>
                                      <option value="40" >
                      3 - POLTRONA / SOFA                    </option>
                                      <option value="9" >
                      4 - CADEIRA                    </option>
                                      <option value="29" >
                      5 - GRADE DE MADEIRA P/ ORGAO                    </option>
                                      <option value="43" >
                      6 - REFRIGERADOR/FREEZER/FRIGOBAR                    </option>
                                      <option value="35" >
                      7 - MESA                    </option>
                                      <option value="3" >
                      8 - ARMARIO                    </option>
                                      <option value="23" >
                      9 - EQUIPAMENTOS DE LIMPEZA                    </option>
                                      <option value="41" >
                      11 - PRATELEIRA / ESTANTE                    </option>
                                      <option value="5" >
                      12 - BALCAO/BANCADA                    </option>
                                      <option value="8" >
                      13 - BEBEDOURO DAGUA / PURIFICADOR DE AGUA                    </option>
                                      <option value="49" >
                      14 - VENTILADOR                    </option>
                                      <option value="44" >
                      15 - RELOGIO DE PAREDE                    </option>
                                      <option value="39" >
                      16 - PAINEL DE CONTROLE DE SOM                    </option>
                                      <option value="11" >
                      17 - CAIXA DE SOM                    </option>
                                      <option value="36" >
                      18 - MICROFONE                    </option>
                                      <option value="15" >
                      19 - COMPUTADOR (CPU+MOUSE+TECLADO) / NOTEBOOK                    </option>
                                      <option value="30" >
                      20 - IMPRESSORA                    </option>
                                      <option value="38" >
                      21 - ORGAO E INSTRUMENTOS                    </option>
                                      <option value="12" >
                      22 - CALCULADORA                    </option>
                                      <option value="19" >
                      23 - EQUIPAMENTO DE ESCRITÓRIO                    </option>
                                      <option value="28" >
                      26 - FORNO / FOGAO / MICROONDAS                    </option>
                                      <option value="46" >
                      50 - TERRENO                    </option>
                                      <option value="20" >
                      51 - EQUIPAMENTO MEDICO HOSPITALAR                    </option>
                                      <option value="24" >
                      55 - ESCADA                    </option>
                                      <option value="26" >
                      56 - EXTINTOR                    </option>
                                      <option value="33" >
                      57 - LAVADORAS / TANGUE ELETRICO                    </option>
                                      <option value="25" >
                      58 - ESTANTES MUSICAIS E DE PARTITURAS / QUADRO MUSICAL                    </option>
                                      <option value="16" >
                      60 - CONSTRUCAO                    </option>
                                      <option value="10" >
                      61 - CAIXA DE COLETA                    </option>
                                      <option value="37" >
                      63 - MONITOR /DATA SHOW                    </option>
                                      <option value="27" >
                      65 - FERRAMENTAS E MAQUINAS                    </option>
                                      <option value="21" >
                      68 - EQUIPAMENTOS DE CLIMATIZAÇÃO                    </option>
                                      <option value="42" >
                      70 - REFORMA                    </option>
                                  </select>
              </div>

              <div class="mb-3">
                <label for="filtro_bem" class="form-label">Bem</label>
                <select id="filtro_bem" name="filtro_bem" class="form-select">
                  <option value="">Todos</option>
                                      <option value="ARMÁRIO" >
                      ARMÁRIO                    </option>
                                      <option value="BALCAO" >
                      BALCAO                    </option>
                                      <option value="BANCO DE MADEIRA" >
                      BANCO DE MADEIRA                    </option>
                                      <option value="BEBEDOURO DAGUA" >
                      BEBEDOURO DAGUA                    </option>
                                      <option value="CADEIRA" >
                      CADEIRA                    </option>
                                      <option value="CAIXA DE COLETA" >
                      CAIXA DE COLETA                    </option>
                                      <option value="CAIXA DE SOM" >
                      CAIXA DE SOM                    </option>
                                      <option value="CALCULADORA" >
                      CALCULADORA                    </option>
                                      <option value="CONSTRUÇÃO" >
                      CONSTRUÇÃO                    </option>
                                      <option value="EQUIPAMENTO DE CLIMATIZAÇÃO" >
                      EQUIPAMENTO DE CLIMATIZAÇÃO                    </option>
                                      <option value="EQUIPAMENTO DE ESCRITÓRIO" >
                      EQUIPAMENTO DE ESCRITÓRIO                    </option>
                                      <option value="EQUIPAMENTO MEDICO HOSPITALAR" >
                      EQUIPAMENTO MEDICO HOSPITALAR                    </option>
                                      <option value="EQUIPAMENTOS DE CLIMATIZAÇÃO" >
                      EQUIPAMENTOS DE CLIMATIZAÇÃO                    </option>
                                      <option value="EQUIPAMENTOS DE LIMPEZA" >
                      EQUIPAMENTOS DE LIMPEZA                    </option>
                                      <option value="ESCADA" >
                      ESCADA                    </option>
                                      <option value="ESTANTES MUSICAIS E DE PARTITURAS" >
                      ESTANTES MUSICAIS E DE PARTITURAS                    </option>
                                      <option value="EXTINTOR" >
                      EXTINTOR                    </option>
                                      <option value="FERRAMENTAS E MÁQUINAS" >
                      FERRAMENTAS E MÁQUINAS                    </option>
                                      <option value="FORNO" >
                      FORNO                    </option>
                                      <option value="FREEZER" >
                      FREEZER                    </option>
                                      <option value="GENUFLEXÓRIO" >
                      GENUFLEXÓRIO                    </option>
                                      <option value="GRADE DE MADEIRA P" >
                      GRADE DE MADEIRA P                    </option>
                                      <option value="IMPRESSORA" >
                      IMPRESSORA                    </option>
                                      <option value="LAVADORA" >
                      LAVADORA                    </option>
                                      <option value="MESA" >
                      MESA                    </option>
                                      <option value="MICROFONE" >
                      MICROFONE                    </option>
                                      <option value="MONITOR" >
                      MONITOR                    </option>
                                      <option value="NOTEBOOK" >
                      NOTEBOOK                    </option>
                                      <option value="ORGAO E INSTRUMENTOS" >
                      ORGAO E INSTRUMENTOS                    </option>
                                      <option value="ÓRGÃOS E INSTRUMENTOS" >
                      ÓRGÃOS E INSTRUMENTOS                    </option>
                                      <option value="OUTROS" >
                      OUTROS                    </option>
                                      <option value="PAINEL DE CONTROLE DE SOM" >
                      PAINEL DE CONTROLE DE SOM                    </option>
                                      <option value="POLTRONA" >
                      POLTRONA                    </option>
                                      <option value="PRATELEIRA" >
                      PRATELEIRA                    </option>
                                      <option value="REFORMA" >
                      REFORMA                    </option>
                                      <option value="REFRIGERADOR" >
                      REFRIGERADOR                    </option>
                                      <option value="RELÓGIO DE PAREDE" >
                      RELÓGIO DE PAREDE                    </option>
                                      <option value="TERRENO" >
                      TERRENO                    </option>
                                      <option value="TRIBUNA" >
                      TRIBUNA                    </option>
                                      <option value="VENTILADOR" >
                      VENTILADOR                    </option>
                                  </select>
              </div>

              <div class="mb-3">
                <label for="filtro_dependencia" class="form-label">DEPENDNCIA</label>
                <select id="filtro_dependencia" name="filtro_dependencia" class="form-select">
                  <option value="">Todas</option>
                                      <option value="7" >
                      ALMOXARIFADO                    </option>
                                      <option value="4" >
                      ATRIO ESQUERDO                    </option>
                                      <option value="3" >
                      COZINHA                    </option>
                                      <option value="10" >
                      ESPACO INFANTIL                    </option>
                                      <option value="5" >
                      REFEITÓRIO                    </option>
                                      <option value="2" >
                      SALAO DE CULTO                    </option>
                                      <option value="6" >
                      SANITARIO MASCULINO                    </option>
                                      <option value="1" >
                      TEMPLO                    </option>
                                  </select>
              </div>

              <div class="mb-3">
                <label for="filtro_STATUS" class="form-label">STATUS</label>
                <select id="filtro_STATUS" name="filtro_STATUS" class="form-select">
                  <option value="">Todos</option>
                  <option value="com_nota" >Com Nota</option>
                  <option value="com_14_1" >Com 14.1</option>
                  <option value="sem_STATUS" >Sem STATUS</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-3">
        <i class="bi bi-search me-2"></i>
        Filtrar
      </button>
    </form>
  </div>
  <div class="card-footer text-muted small">
    491 registros encontrados
  </div>

</div>

<!-- BOTO DE EXCLUSO EM MASSA (inicialmente oculto) -->
<div id="deleteButtonContainer" class="card mb-3" style="display: none;">
  <div class="card-body">
    <form method="POST" id="deleteForm">
      <input type="hidden" name="id_planilha" value="1">
      <div id="selectedProducts"></div>
      <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja excluir os PRODUTOS selecionados?');">
        <i class="bi bi-trash me-2"></i>
        Excluir <span id="countSelected">0</span> PRODUTO(s) Selecionado(s)
      </button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>
      <i class="bi bi-box-seam me-2"></i>
      PRODUTOS
    </span>
    <span class="badge bg-white text-dark">491 itens (pg. 1/25)</span>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>PRODUTOS</th>
        </tr>
      </thead>
      <tbody>
                  <tr>
            <td class="text-center text-muted py-4">
              <i class="bi bi-inbox fs-1 d-block mb-2"></i>
              Nenhum PRODUTO cadastrado para esta planilha.            </td>
          </tr>
              </tbody>
    </table>
  </div>

      <div class="card-footer">
      <nav>
        <ul class="pagination justify-content-center mb-0">
                    
                      <li class="page-item active">
              <a class="page-link" href="?comum_id=1&pagina=1&comum_id=1">1</a>
            </li>
                      <li class="page-item ">
              <a class="page-link" href="?comum_id=1&pagina=2&comum_id=1">2</a>
            </li>
                      <li class="page-item ">
              <a class="page-link" href="?comum_id=1&pagina=3&comum_id=1">3</a>
            </li>
          
                      <li class="page-item">
              <a class="page-link" href="?comum_id=1&pagina=25&comum_id=1" aria-label="ltima">
                <span aria-hidden="true">»</span>
              </a>
            </li>
                  </ul>
      </nav>
    </div>
  </div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.PRODUTO-checkbox');
    const deleteButtonContainer = document.getElementById('deleteButtonContainer');
    const selectedProductsDiv = document.getElementById('selectedProducts');
    const countSelected = document.getElementById('countSelected');
    const deleteForm = document.getElementById('deleteForm');

    function atualizarContagem() {
      const checados = document.querySelectorAll('.PRODUTO-checkbox:checked').length;
      countSelected.textContent = checados;

      // Mostrar/ocultar container de excluso
      if (checados > 0) {
        deleteButtonContainer.style.display = 'block';
      } else {
        deleteButtonContainer.style.display = 'none';
      }

      // ATUALIZAR inputs ocultos com IDs selecionados
      selectedProductsDiv.innerHTML = '';
      document.querySelectorAll('.PRODUTO-checkbox:checked').forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids_PRODUTOS[]';
        input.value = checkbox.value;
        selectedProductsDiv.appendChild(input);
      });
    }

    // Adicionar listener em cada checkbox
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', atualizarContagem);
    });

    // Enviar form de excluso via POST
    deleteForm.addEventListener('submit', function(e) {
      e.preventDefault();

      // Enviar via AJAX ou formulrio tradicional
      const formData = new FormData(deleteForm);

      fetch('./PRODUTOS_excluir.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Recarregar a pgina
            location.reload();
          } else {
            alert('Erro ao excluir PRODUTOS: ' + (data.message || 'Erro desconhecido'));
          }
        })
        .catch(error => {
          console.error('Erro:', error);
          alert('Erro ao excluir PRODUTOS');
        });
    });
  });
</script>

