// Drag and Drop Functionality for Sortable List 
let draggedElement;
let draggedIndex;


/* 
 * FUNCTION: Handles the start of dragging an element 
 */
export function handleDragStart(event, element, index) {
    draggedElement = element; // Store the dragged element
    draggedIndex = index; // Store the dragged index
    event.dataTransfer.effectAllowed = 'move'; // Set the allowed effect
    event.dataTransfer.setData('text/plain', index); // Store the dragged index

    setTimeout(() => {
        element.style.opacity = '0.5'; // Make it semi-transparent
    }, 0);
}

// Attach handleDragStart to the window object for global access
window.handleDragStart = handleDragStart;


/* 
 * FUNCTION: Handles the end of dragging an element 
 */
export function handleDragEnd(element) {
    element.style.opacity = '1'; // Reset the opacity
}

// Attach handleDragEnd to the window object for global access
window.handleDragEnd = handleDragEnd;


/* 
 * Allow dropping in the sortable list 
 */

const sortableList = document.getElementById('sortable-list');

sortableList.addEventListener('dragover', (event) => {
    event.preventDefault(); // Allow dropping
    event.dataTransfer.dropEffect = 'move'; // Visual feedback
});

/* 
 * Handle drop events on the sortable list 
 */
sortableList.addEventListener('drop', (event) => {
    const index = Array.from(sortableList.children).indexOf(event.target.closest('.list-group-item'));
    drop(event, index); // Call drop function with the index
});


/* 
 * FUNCTION: Handles dropping the dragged element 
 */
function drop(event, index) {
    event.preventDefault(); // Prevent default behavior

    if (draggedIndex === index) {
        return; // If the index is the same, do nothing
    }

    requestAnimationFrame(() => {
        // Remove the dragged element from its original position
        sortableList.removeChild(draggedElement);

        // Insert the dragged element at the new position
        if (index < draggedIndex) {
            sortableList.insertBefore(draggedElement, sortableList.children[index]);
        } else {
            sortableList.insertBefore(draggedElement, sortableList.children[index + 1]);
        }

        // Clear the highlighted class from all items
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.classList.remove('drag-over');
        });

        // Update the Livewire items after reordering. No longer needed since data will be fetched from db.
        
        // const newOrder = Array.from(sortableList.children).map(child => child.querySelector('.col-10').innerText.trim());
        // Livewire.emit('updateItems', newOrder);
    });
}

/* 
 * Highlight the target item while dragging over it 
 */
document.querySelectorAll('.list-group-item').forEach(item => {
    item.addEventListener('dragover', (event) => {
        event.preventDefault(); // Allow dropping
        item.classList.add('drag-over'); // Highlight item
    });

    item.addEventListener('dragleave', () => {
        item.classList.remove('drag-over'); // Remove highlight
    });

    item.addEventListener('drop', () => {
        item.classList.remove('drag-over'); // Ensure highlight is removed on drop
    });
});

// Attach the drop function to the window object for global access
window.drop = drop;
