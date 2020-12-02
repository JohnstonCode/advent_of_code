const inputs: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).trim().split("\n");

const regex = /^(\d+)-(\d+)\s(\w):\s(.*)$/g;
let part1 = 0;
let part2 = 0;

for (const line of inputs) {
  const matches: string[] = Array.from(line.matchAll(regex))[0];
  const [, lo, high, letter, password] = matches;
  const count = password.split(letter).length - 1;
  const letterArray = password.split("");

  if (count >= +lo && count <= +high) {
    part1 += 1;
  }

  if (
    (letterArray[+lo - 1] === letter ? 1 : 0) ^
    (letterArray[+high - 1] === letter ? 1 : 0)
  ) {
    part2 += 1;
  }
}

console.log(`Part 1: ${part1}`);
console.log(`Part 2: ${part2}`);
