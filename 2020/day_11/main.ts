const input: string[][] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x).map((line) => line.split(""));

function occupiedAdjacentSeatCount(
  map: string[][],
  x: number,
  y: number,
): number {
  // [x, y]
  const dirs = [
    [0, -1], //top
    [0, 1], //bottom
    [-1, 0], //left
    [1, 0], //right
    [-1, -1], //top left
    [1, -1], //top right
    [-1, 1], //bottom left
    [1, 1], //bottom right
  ];
  let count = 0;

  for (const [xx, yy] of dirs) {
    const xxx = x + xx;
    const yyy = y + yy;

    if (map[yyy] && map[yyy][xxx] === "#") {
      count++;
    }
  }

  return count;
}

function run(grid: string[][]): string[][] {
  const tmpGrid = JSON.parse(JSON.stringify(grid));

  for (let y = 0; y < grid.length; y++) {
    for (let x = 0; x < grid[y].length; x++) {
      if (grid[y][x] === ".") {
        continue;
      }

      if (grid[y][x] === "L" && occupiedAdjacentSeatCount(grid, x, y) === 0) {
        tmpGrid[y][x] = "#";
      }

      if (grid[y][x] === "#" && occupiedAdjacentSeatCount(grid, x, y) >= 4) {
        tmpGrid[y][x] = "L";
      }
    }
  }

  return tmpGrid;
}

let previousSeats = JSON.parse(JSON.stringify(input));
let currentSeats = run(previousSeats);

while (previousSeats.toString() !== currentSeats.toString()) {
  previousSeats = [...currentSeats];
  currentSeats = run(previousSeats);
}

const part1 = currentSeats.flat().filter((x) => x === "#").length;

console.log(`Part 1: ${part1}`);

function getSeatCountInAllDirections(
  map: string[][],
  x: number,
  y: number,
): number {
  // [x, y]
  const dirs = [
    [0, -1], //top
    [0, 1], //bottom
    [-1, 0], //left
    [1, 0], //right
    [-1, -1], //top left
    [1, -1], //top right
    [-1, 1], //bottom left
    [1, 1], //bottom right
  ];

  let count = 0;

  for (const [xx, yy] of dirs) {
    let xxx = x + xx;
    let yyy = y + yy;

    while (map[yyy] && map[yyy][xxx]) {
      if (map[yyy][xxx] === "L") {
        break;
      }

      if (map[yyy][xxx] === "#") {
        count++;

        break;
      }

      xxx += xx;
      yyy += yy;
    }
  }

  return count;
}

function run2(grid: string[][]): string[][] {
  const tmpGrid = JSON.parse(JSON.stringify(grid));

  for (let y = 0; y < grid.length; y++) {
    for (let x = 0; x < grid[y].length; x++) {
      if (grid[y][x] === ".") {
        continue;
      }

      if (grid[y][x] === "L" && getSeatCountInAllDirections(grid, x, y) === 0) {
        tmpGrid[y][x] = "#";
      }

      if (grid[y][x] === "#" && getSeatCountInAllDirections(grid, x, y) >= 5) {
        tmpGrid[y][x] = "L";
      }
    }
  }

  return tmpGrid;
}

let previousSeats2 = JSON.parse(JSON.stringify(input));
let currentSeats2 = run2(previousSeats2);

while (previousSeats2.toString() !== currentSeats2.toString()) {
  previousSeats2 = [...currentSeats2];
  currentSeats2 = run2(previousSeats2);
}

const part2 = currentSeats2.flat().filter((x) => x === "#").length;

console.log(`Part 2: ${part2}`);
