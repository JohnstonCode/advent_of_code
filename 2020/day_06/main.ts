const groups: string[] = (await Deno.readTextFile(
    new URL(".", import.meta.url).pathname + "/input.txt",
  )).split("\n\n").filter(x => x);

let part1 = 0;
let part2 = 0;

for (const group of groups) {
    const uniques = new Set([...group.replace(/\n/g, '')]);
    
    part1 += uniques.size;

    part2 += [...uniques].filter(char => group.split("\n").filter(x => x).every(form => form.includes(char))).length;
}

console.log(`Part 1: ${part1}`);
console.log(`Part 2: ${part2}`);
