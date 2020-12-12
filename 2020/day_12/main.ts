const instructions: [string, number][] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x).map((line) => {
  const action = line.slice(0, 1);
  const value = Number(line.slice(1, line.length));

  return [action, value];
});

function rotate(
  currentDegrees: number,
  turn: string,
  degrees: number,
): number {
  if (turn === "R") {
    currentDegrees += degrees;
  }

  if (turn === "L") {
    currentDegrees -= degrees;
  }

  if (currentDegrees >= 360) {
    currentDegrees -= 360;
  }

  if (currentDegrees < 0) {
    currentDegrees += 360;
  }

  return currentDegrees;
}

function part1(instructions: [string, number][]) {
  let degrees = 90;
  let x = 0;
  let y = 0;

  for (const instruction of instructions) {
    const [action, value] = instruction;

    switch (action) {
      case "N":
        y -= value;
        break;
      case "S":
        y += value;
        break;
      case "E":
        x += value;
        break;
      case "W":
        x -= value;
        break;
      case "L":
        degrees = rotate(degrees, action, value);
        break;
      case "R":
        degrees = rotate(degrees, action, value);
        break;
      case "F":
        if (degrees === 0) y -= value;
        if (degrees === 180) y += value;
        if (degrees === 270) x -= value;
        if (degrees === 90) x += value;
        break;
      default:
        throw new Error("Not implimented");
    }
  }

  return Math.abs(x) + Math.abs(y);
}

function part2(instructions: [string, number][]) {
  let x = 0;
  let y = 0;
  let wX = 10;
  let wY = -1;

  for (const instruction of instructions) {
    const [action, value] = instruction;
    let angle = value / 90;

    switch (action) {
      case "N":
        wY -= value;
        break;
      case "S":
        wY += value;
        break;
      case "E":
        wX += value;
        break;
      case "W":
        wX -= value;
        break;
      case "L":
        while (angle--) {
          [wX, wY] = [wY, -wX];
        }
        break;
      case "R":
        while (angle--) {
          [wX, wY] = [-wY, wX];
        }
        break;
      case "F":
        [x, y] = [x + wX * value, y + wY * value];
        break;
      default:
        throw new Error("Not implimented");
    }
  }

  return Math.abs(x) + Math.abs(y);
}

console.log(`Part 1: ${part1(instructions)}`);
console.log(`Part 2: ${part2(instructions)}`);
