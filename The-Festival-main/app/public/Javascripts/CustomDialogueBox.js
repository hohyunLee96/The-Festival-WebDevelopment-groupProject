function displayModalForDelete() {
    // Create the modal
    let modal = document.createElement("div");
    modal.classList.add("modal", "fade");
    modal.id = "myModal";
    modal.setAttribute("tabindex", "-1");
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-labelledby", "myModalLabel");
    modal.setAttribute("aria-hidden", "true");
    document.body.appendChild(modal);

    // Create the modal dialog
    let modalDialog = document.createElement("div");
    modalDialog.classList.add("modal-dialog");
    modalDialog.setAttribute("role", "document");
    modal.appendChild(modalDialog);

    // Create the modal content
    let modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");
    modalDialog.appendChild(modalContent);

    // Create the modal header
    let modalHeader = document.createElement("div");
    modalHeader.classList.add("modal-header");
    modalContent.appendChild(modalHeader);

    // Create the modal title
    let modalTitle = document.createElement("h5");
    modalTitle.classList.add("modal-title");
    modalTitle.innerText = "Confirmation";
    modalTitle.id = "myModalLabel";
    modalHeader.appendChild(modalTitle);

    // Create the modal close button
    let modalCloseBtn = document.createElement("button");
    modalCloseBtn.type = "button";
    modalCloseBtn.classList.add("btn-close");
    modalCloseBtn.setAttribute("data-bs-dismiss", "modal");
    modalCloseBtn.setAttribute("aria-label", "Close");
    modalHeader.appendChild(modalCloseBtn);

    // Create the modal body
    let modalBody = document.createElement("div");
    modalBody.classList.add("modal-body");
    modalBody.innerText = "Are you sure you want to delete?";
    modalContent.appendChild(modalBody);

    // Create the modal footer
    let modalFooter = document.createElement("div");
    modalFooter.classList.add("modal-footer");
    modalContent.appendChild(modalFooter);

    // Create the modal close button
    let modalCloseBtn2 = document.createElement("button");
    modalCloseBtn2.type = "button";
    modalCloseBtn2.classList.add("btn", "btn-secondary");
    modalCloseBtn2.innerText = "No";
    modalCloseBtn2.setAttribute("data-bs-dismiss", "modal");
    modalFooter.appendChild(modalCloseBtn2);

    let modalYesBtn = document.createElement("button");
    modalYesBtn.type = "button";
    modalYesBtn.classList.add("btn", "btn-danger");
    modalYesBtn.innerText = "Yes";
    modalFooter.appendChild(modalYesBtn);

    // Show the modal
    let modalBtn = document.createElement("button");
    modalBtn.type = "button";
    modalBtn.hidden = true;
    modalBtn.setAttribute("data-bs-toggle", "modal");
    modalBtn.setAttribute("data-bs-target", "#myModal");
    document.body.appendChild(modalBtn);
    modalBtn.click();

    // Create a Promise that resolves with the user's choice
    return new Promise((resolve) => {
        modalYesBtn.addEventListener("click", function () {
            resolve(true);
            modalCloseBtn.click();
        });
        modalCloseBtn2.addEventListener("click", function () {
            resolve(false);
            modalCloseBtn.click();
        });
    });
}

function displayModal(title, message) {
    // create modal HTML elements
    const modal = document.createElement("div");
    modal.classList.add("modal", "fade");
    modal.setAttribute("tabindex", "-1");
    modal.setAttribute("role", "dialog");
    modal.innerHTML = `
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">${title}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ${message}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="modal-ok-button">OK</button>
        </div>
      </div>
    </div>
  `;

    // append modal to the document
    document.body.appendChild(modal);

    // show modal
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();

    // add event listener to OK button to reload the page
    const okButton = modal.querySelector("#modal-ok-button");
    okButton.addEventListener("click", () => {
        location.reload();
    });

    // remove modal from the document when closed
    modal.addEventListener("hidden.bs.modal", () => {
        document.body.removeChild(modal);
    });
}

