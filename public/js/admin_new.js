document.addEventListener('DOMContentLoaded', function () {
     const createCategoryButton = document.getElementById('create-category');
     const categoryForm = document.getElementById('category-form');
     const categoryTableBody = document.querySelector('#category-table tbody');
     const saveCategoryButton = document.getElementById('save-category');
     const cancelCategoryButton = document.getElementById('cancel-category');
     const categoryNameInput = document.getElementById('category-name');
 

     // Afficher le formulaire d'ajout de catégorie
     createCategoryButton.addEventListener('click', function () {
         categoryForm.style.display = 'block';
     });
 

     // Cacher le formulaire
     cancelCategoryButton.addEventListener('click', function () {
         categoryForm.style.display = 'none';
         categoryNameInput.value = '';
     });
 

     // Enregistrer une catégorie (simulé)
     saveCategoryButton.addEventListener('click', function () {
         const categoryName = categoryNameInput.value.trim();
         if (categoryName) {
             // Simuler l'ajout à la table
             const newRow = categoryTableBody.insertRow();
             const idCell = newRow.insertCell(0);
             const nameCell = newRow.insertCell(1);
             const actionsCell = newRow.insertCell(2);
 

             idCell.textContent = categoryTableBody.rows.length; // Simuler un ID
             nameCell.textContent = categoryName;
             actionsCell.innerHTML = '<button class="edit-category">Modifier</button> <button class="delete-category">Supprimer</button>';
 

             // Effacer le formulaire et le cacher
             categoryNameInput.value = '';
             categoryForm.style.display = 'none';
         } else {
             alert('Veuillez entrer un nom de catégorie.');
         }
     });
 

     // Gestion des actions (modifier/supprimer)
     categoryTableBody.addEventListener('click', function (event) {
         if (event.target.classList.contains('edit-category')) {
             const row = event.target.closest('tr');
             const nameCell = row.cells[1];
             const newName = prompt('Nouveau nom de la catégorie:', nameCell.textContent);
             if (newName) {
                 nameCell.textContent = newName;
             }
         } else if (event.target.classList.contains('delete-category')) {
             const row = event.target.closest('tr');
             if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                 categoryTableBody.deleteRow(row.rowIndex);
             }
         }
     });
 });