class MedicinesApp {
    constructor() {
        this.draggedItem = null;
        this.originalOrder = [];
        this.init();
    }
    
    init() {
        this.initDragAndDrop();
        this.initTakenButtons();
        this.loadOrderFromStorage();
        this.loadTakenStatusFromStorage();
        this.updateStatistics();
        
        this.saveOriginalOrder();
    }
    
    
    initDragAndDrop() {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        items.forEach(item => this.addDragHandlers(item));
        
        medicinesList.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
    }
    
    addDragHandlers(item) {
        item.setAttribute('draggable', 'true');
        
        item.addEventListener('dragstart', (e) => {
            this.draggedItem = item;
            e.dataTransfer.setData('text/plain', item.getAttribute('data-id'));
            e.dataTransfer.effectAllowed = 'move';
            
            setTimeout(() => {
                item.classList.add('dragging');
            }, 0);
        });
        
        item.addEventListener('dragend', (e) => {
            item.classList.remove('dragging');
            
            document.querySelectorAll('.medicine-item').forEach(i => {
                i.classList.remove('drag-over');
            });
            
            this.draggedItem = null;
        });
        
        item.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        
        item.addEventListener('dragenter', (e) => {
            e.preventDefault();
            if (item !== this.draggedItem) {
                item.classList.add('drag-over');
            }
        });
        
        item.addEventListener('dragleave', (e) => {
            item.classList.remove('drag-over');
        });
        
        item.addEventListener('drop', (e) => {
            e.preventDefault();
            item.classList.remove('drag-over');
            
            if (!this.draggedItem || this.draggedItem === item) return;
            
            const parent = item.parentNode;
            const draggedIndex = Array.from(parent.children).indexOf(this.draggedItem);
            const targetIndex = Array.from(parent.children).indexOf(item);
            
            if (draggedIndex < targetIndex) {
                parent.insertBefore(this.draggedItem, item.nextSibling);
            } else {
                parent.insertBefore(this.draggedItem, item);
            }
            
            this.updateOrderNumbers();
            this.saveOrderToStorage();
            
            this.animateOrderUpdate();
        });
    }
    
    updateOrderNumbers() {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        items.forEach((item, index) => {
            const orderNumber = item.querySelector('.order-number');
            if (orderNumber) {
                orderNumber.textContent = index + 1;
            }
            item.setAttribute('data-order', index);
        });
    }
    
    saveOrderToStorage() {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        const order = [];
        const today = new Date().toDateString();
        
        items.forEach(item => {
            const id = item.getAttribute('data-id');
            if (id) {
                order.push(parseInt(id));
            }
        });
        
        localStorage.setItem(`medicines_order_${today}`, JSON.stringify(order));
    }
    
    loadOrderFromStorage() {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const today = new Date().toDateString();
        const savedOrder = localStorage.getItem(`medicines_order_${today}`);
        
        if (savedOrder) {
            try {
                const order = JSON.parse(savedOrder);
                this.applyOrder(order);
                console.log('Порядок загружен:', order);
            } catch(e) {
                console.error('Ошибка загрузки порядка:', e);
            }
        }
    }
    
    applyOrder(order) {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        const itemsMap = new Map();
        
        items.forEach(item => {
            const id = item.getAttribute('data-id');
            if (id) {
                itemsMap.set(parseInt(id), item);
            }
        });
        
        const sortedItems = [];
        order.forEach(id => {
            const item = itemsMap.get(id);
            if (item) {
                sortedItems.push(item);
                itemsMap.delete(id);
            }
        });
        
        itemsMap.forEach(item => {
            sortedItems.push(item);
        });
        
        sortedItems.forEach(item => {
            medicinesList.appendChild(item);
        });
        
        this.updateOrderNumbers();
        this.reinitDragAndDrop();
        this.initTakenButtons();
    }
    
    reinitDragAndDrop() {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        items.forEach(item => {
            const newItem = item.cloneNode(true);
            item.parentNode.replaceChild(newItem, item);
        });
        
        this.initDragAndDrop();
    }
    
    saveOriginalOrder() {
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        this.originalOrder = [];
        items.forEach(item => {
            const id = item.getAttribute('data-id');
            if (id) {
                this.originalOrder.push(parseInt(id));
            }
        });
    }
    
    resetOrder() {
        if (this.originalOrder.length === 0) return;
        
        const medicinesList = document.getElementById('medicinesList');
        if (!medicinesList) return;
        
        const items = medicinesList.querySelectorAll('.medicine-item');
        const itemsMap = new Map();
        
        items.forEach(item => {
            const id = item.getAttribute('data-id');
            if (id) {
                itemsMap.set(parseInt(id), item);
            }
        });
        
        const sortedItems = [];
        this.originalOrder.forEach(id => {
            const item = itemsMap.get(id);
            if (item) {
                sortedItems.push(item);
            }
        });
        
        sortedItems.forEach(item => {
            medicinesList.appendChild(item);
        });
        
        this.updateOrderNumbers();
        this.saveOrderToStorage();
        
        this.animateReset();
    }
    
    animateOrderUpdate() {
        const orderDiv = document.querySelector('.statistics');
        if (orderDiv) {
            orderDiv.style.transform = 'scale(1.02)';
            setTimeout(() => {
                orderDiv.style.transform = 'scale(1)';
            }, 200);
        }
    }
    
    animateReset() {
        const orderDiv = document.querySelector('.statistics');
        if (orderDiv) {
            orderDiv.style.background = '#d4edda';
            setTimeout(() => {
                orderDiv.style.background = '';
            }, 500);
        }
    }
    
    
    initTakenButtons() {
        const buttons = document.querySelectorAll('.btn-taken');
        buttons.forEach(button => {
            button.removeEventListener('click', this.handleTakenClick);
            button.addEventListener('click', this.handleTakenClick.bind(this));
        });
    }
    
    handleTakenClick(e) {
        e.preventDefault();
        e.stopPropagation();
        const button = e.currentTarget;
        this.toggleTaken(button);
    }
    
    toggleTaken(button) {
        const medicineItem = button.closest('.medicine-item');
        const id = medicineItem.getAttribute('data-id');
        const today = new Date().toDateString();
        const isTaken = button.classList.contains('taken');
        
        if (isTaken) {
            button.classList.remove('taken');
            const statusSpan = button.querySelector('.taken-status');
            if (statusSpan) {
                statusSpan.textContent = '❌ Не принято';
            }
            button.style.background = '#f0f0f0';
            button.style.color = '#333';
            medicineItem.classList.remove('taken-medicine');
            localStorage.removeItem(`taken_${id}_${today}`);
        } else {
            button.classList.add('taken');
            const statusSpan = button.querySelector('.taken-status');
            if (statusSpan) {
                statusSpan.textContent = '✅ Принято';
            }
            button.style.background = '#4caf50';
            button.style.color = 'white';
            medicineItem.classList.add('taken-medicine');
            localStorage.setItem(`taken_${id}_${today}`, 'true');
        }
        
        this.updateStatistics();
    }
    
    loadTakenStatusFromStorage() {
        const today = new Date().toDateString();
        const buttons = document.querySelectorAll('.btn-taken');
        
        buttons.forEach(button => {
            const medicineItem = button.closest('.medicine-item');
            const id = medicineItem.getAttribute('data-id');
            const isTaken = localStorage.getItem(`taken_${id}_${today}`) === 'true';
            
            if (isTaken) {
                button.classList.add('taken');
                const statusSpan = button.querySelector('.taken-status');
                if (statusSpan) {
                    statusSpan.textContent = '✅ Принято';
                }
                button.style.background = '#4caf50';
                button.style.color = 'white';
                medicineItem.classList.add('taken-medicine');
            }
        });
        
        this.updateStatistics();
    }
    
    updateStatistics() {
        const takenButtons = document.querySelectorAll('.btn-taken.taken');
        const totalButtons = document.querySelectorAll('.btn-taken');
        const takenCount = takenButtons.length;
        const totalCount = totalButtons.length;
        const remainingCount = totalCount - takenCount;
        
        const takenCountElem = document.getElementById('takenCount');
        const remainingCountElem = document.getElementById('remainingCount');
        const progressFill = document.getElementById('progressFill');
        
        if (takenCountElem) takenCountElem.textContent = takenCount;
        if (remainingCountElem) remainingCountElem.textContent = remainingCount;
        
        if (progressFill && totalCount > 0) {
            const percent = (takenCount / totalCount) * 100;
            progressFill.style.width = percent + '%';
            progressFill.textContent = Math.round(percent) + '%';
        } else if (progressFill) {
            progressFill.style.width = '0%';
            progressFill.textContent = '0%';
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    window.medicinesApp = new MedicinesApp();
});