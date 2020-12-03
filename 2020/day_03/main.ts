const map = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).trim().split("\n").map((row) => row.split(""));

const bottom = map.length;
let currentLevel = 1;
const maxX = map[0].length;
let x = 0;
let y = 0;
let treesFound = 0;

while (currentLevel < bottom) {
  x += 3;
  y += 1;

  if (x >= maxX) {
    x = x % maxX;
  }

  if (map[y][x] === "#") {
    treesFound += 1;
  }

  currentLevel += 1;
}

console.log(`Part 1: ${treesFound}`);

const slopes = [
  [1, 1],
  [3, 1],
  [5, 1],
  [7, 1],
  [1, 2],
];
const treeCounts = [];

for (const slope of slopes) {
  const [xAdd, yAdd] = slope;
  currentLevel = 1;
  treesFound = 0;
  x = 0;
  y = 0;

  while (currentLevel < bottom) {
    x += xAdd;
    y += yAdd;

    if (x >= maxX) {
      x = x % maxX;
    }

    if (map[y][x] === "#") {
      treesFound += 1;
    }

    currentLevel += yAdd;
  }

  treeCounts.push(treesFound);
}

const treeTotal = treeCounts.reduce((a, b) => a * b, 1);

console.log(`Part 2: ${treeTotal}`);
