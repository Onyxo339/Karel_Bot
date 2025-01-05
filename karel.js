const boardSize = 10;
const board = document.getElementById('game-board');
const commandsInput = document.getElementById('commands');
const runButton = document.getElementById('run');

let karel = { x: 0, y: 0, direction: 0 };
function resetBoard() {
    karel = {x: 0, y: 0, direction: 0};
    board.innerHTML = '';
    for (let i = 0; i < boardSize; i++) {
        for (let j = 0; j < boardSize; j++) {
            const cell = document.createElement('div');
            cell.dataset.x = j;
            cell.dataset.y = i;
            board.appendChild(cell);
        }
    }
    updateKarelPosition();
}

function updateKarelPosition() {
    const cells = board.querySelectorAll('div');
    cells.forEach(cell => {
        if (!cell.querySelector('.item')) {
            cell.textContent = '';
        }
    });

    cells.forEach(cell => cell.classList.remove('karel'));

    const currentCell = board.querySelector(`div[data-x="${karel.x}"][data-y="${karel.y}"]`);
    currentCell.classList.add('karel');
    currentCell.textContent = 'K';
}

function changeBlockColor() {
    const currentCell = board.querySelector(`div[data-x="${karel.x}"][data-y="${karel.y}"]`); 
    currentCell.style.backgroundColor = "blue"; 
}

function executeCommand(command) {
    const parts = command.trim().split(' ');
    const action = parts[0].toUpperCase();
    const param = parts.length > 1 ? parseInt(parts[1], 10) : 1;

    switch (action) {
        case 'KROK':
            for (let i = 0; i < param; i++) moveKarel();
            break;
        case 'VLEVOBOK':
            karel.direction = (karel.direction + param) % 4;
            break;
        case 'POLOZ':
            changeBlockColor();
            break;
        case 'RESET':
            resetBoard();
            break;
        case 'RESET KAREL':
            karel = { x: 0, y: 0, direction: 0};
            updateKarelPosition();
        default:
            console.error('Neznámý příkaz:', command);
    }
}

function moveKarel() {
    switch (karel.direction) {
        case 0: if (karel.x < boardSize - 1) karel.x++; break;
        case 1: if (karel.y < boardSize - 1) karel.y++; break;
        case 2: if (karel.x > 0) karel.x--; break;
        case 3: if (karel.y > 0) karel.y--; break;
    }
    updateKarelPosition();
}

runButton.addEventListener('click', () => {
    const commands = commandsInput.value.split('\n').filter(cmd => cmd.trim() !== '');
    commands.forEach(command => executeCommand(command));
});

resetBoard();