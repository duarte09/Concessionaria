function excluirCard(event) {
    const buttonClicked = event.target; // seleciona exatamente o botão que foi clicado
    const card = buttonClicked.closest('.card'); // seleciona o card em que o botão está inserido
    if (card) {
        card.remove();
    }
}

document.addEventListener("DOMContentLoaded", function () { // aguarda o carregamento completo do DOM
    document.querySelectorAll('.btn-excluir').forEach(button => {  // seleciona todos os botões e no botão que houver o evento de click, este será selecionado para chamar a function
        button.addEventListener('click', excluirCard);
    });
});

function excluirInteresse(event) {
    const buttonClicked = event.target; 
    const interesseItem = buttonClicked.closest('.list-group-item'); 
    if (interesseItem) {
      interesseItem.remove(); 
    }
  }
  
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.btn-ex').forEach(button => {
      button.addEventListener('click', excluirInteresse);
    });
  });
  