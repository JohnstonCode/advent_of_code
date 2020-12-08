const instructions: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x);

function runBootCode(instrutions: string[], infinateReturn = false) {
  let acc = 0;
  let index = 0;
  const visited = new Set();

  while (true) {
    if (!instructions[index]) {
      return acc;
    }

    if (visited.has(index)) {
      if (infinateReturn) {
        return acc;
      }

      return false;
    }

    visited.add(index);

    const [opp, offset] = instructions[index].split(" ");

    switch (opp) {
      case "acc":
        acc = eval(`${acc} ${offset}`);
        index += 1;
        break;
      case "jmp": 
        index = eval(`${index} ${offset}`);
        break;
      case "nop":
        index += 1;
        break;
    }
  }
}

console.log(`Part 1: ${runBootCode(instructions, true)}`);

loop:
for (const [from, to] of [['nop', 'jump'], ['jmp', 'nop']]) {
  for (let i = 0; i < instructions.length; i++) {
    let [opp, offset] = instructions[i].split(" ");

    if (opp === from) {
      opp = to;

      instructions[i] = [opp, offset].join(' ');

      const part2 = runBootCode(instructions);
      if (part2) {
        console.log(`Part 2: ${part2}`);

        break loop;
      }

      opp = from;
      instructions[i] = [opp, offset].join(' ');
    }
  }
}
