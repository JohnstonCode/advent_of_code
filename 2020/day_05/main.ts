const inputs: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).trim().split("\n");

const part1 = Math.max(...inputs.map((input) => {
  return parseInt(input.replace(/F|L/g, "0").replace(/B|R/g, "1"), 2);
}));

console.log(`Part 1: ${part1}`);

const boardingPasses = new Set([...inputs.map((input) => {
  return parseInt(input.replace(/F|L/g, "0").replace(/B|R/g, "1"), 2);
})]);

for (const seatId of boardingPasses.values()) {
  if (!boardingPasses.has(seatId - 1) && boardingPasses.has(seatId - 2)) {
    console.log(`Part 2: ${seatId - 1}`);
  }
}
