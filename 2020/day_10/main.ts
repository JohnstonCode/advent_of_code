const adapters: number[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x).map(Number);

adapters.sort((a, b) => a - b);

const oneJolt = new Set();
const threeJolt = new Set();
let currentRating = 0;

for (const adapter of adapters) {
  if ((adapter - currentRating) === 1) {
    oneJolt.add(adapter);
  }

  if ((adapter - currentRating) === 3) {
    threeJolt.add(adapter);
  }

  currentRating = adapter;
}

const part1 = oneJolt.size * (threeJolt.size + 1);
console.log(`Part 1: ${part1}`);

function findArrangements(adapters: number[]) {
  adapters.unshift(0);
  adapters.push(Math.max(...adapters) + 3);
  const arrangements = new Map([[0, 1]]);

  for (let i = 0; i < adapters.length; i++) {
    let j = i + 1;

    while (adapters[j] <= adapters[i] + 3) {
      arrangements.set(
        j,
        (arrangements.get(j) || 0) + (arrangements.get(i) || 0),
      );
      j++;
    }
  }

  return arrangements.get(adapters.length - 1);
}

console.log(`Part 2: ${findArrangements(adapters)}`);
