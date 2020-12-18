const input: string[][] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x).map((line) => line.split(""));

function create3DGrid(input: string[][]): Map<string, string> {
  const grid = new Map<string, string>();

  for (let y = 0; y < input.length; y++) {
    for (let x = 0; x < input[0].length; x++) {
      grid.set(`${x},${y},0`, input[y][x]);
    }
  }

  return grid;
}

function create4DGrid(input: string[][]): Map<string, string> {
  const grid = new Map<string, string>();

  for (let y = 0; y < input.length; y++) {
    for (let x = 0; x < input[0].length; x++) {
      grid.set(`${x},${y},0,0`, input[y][x]);
    }
  }

  return grid;
}

function get3DNeighbors(
  x: number,
  y: number,
  z: number,
  map: Map<string, string>,
): string[] {
  const neighbors: string[] = [];

  for (let xx = x - 1; xx <= x + 1; xx++) {
    for (let yy = y - 1; yy <= y + 1; yy++) {
      for (let zz = z - 1; zz <= z + 1; zz++) {
        if (xx === x && yy === y && zz === z) continue;

        neighbors.push(map.get(`${xx},${yy},${zz}`) || ".");
      }
    }
  }

  return neighbors;
}

function get4DNeighbors(
  x: number,
  y: number,
  z: number,
  w: number,
  map: Map<string, string>,
): string[] {
  const neighbors: string[] = [];

  for (let xx = x - 1; xx <= x + 1; xx++) {
    for (let yy = y - 1; yy <= y + 1; yy++) {
      for (let zz = z - 1; zz <= z + 1; zz++) {
        for (let ww = w - 1; ww <= w + 1; ww++) {
          if (xx === x && yy === y && zz === z && ww === w) continue;

          neighbors.push(map.get(`${xx},${yy},${zz},${ww}`) || ".");
        }
      }
    }
  }

  return neighbors;
}

let grid3D = create3DGrid(input);

for (let i = 0; i < 6; i++) {
  const xs = [...grid3D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[0])
  );
  const minX = Math.min(...xs);
  const maxX = Math.max(...xs);

  const ys = [...grid3D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[1])
  );
  const minY = Math.min(...ys);
  const maxY = Math.max(...ys);

  const zs = [...grid3D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[2])
  );
  const minZ = Math.min(...zs);
  const maxZ = Math.max(...zs);

  const newGrid = new Map<string, string>();

  for (let x = minX - 1; x <= maxX + 1; x++) {
    for (let y = minY - 1; y <= maxY + 1; y++) {
      for (let z = minZ - 1; z <= maxZ + 1; z++) {
        const neigbours = get3DNeighbors(x, y, z, grid3D);
        const activeNeigbours = neigbours.filter((x) => x === "#").length;
        const key = `${x},${y},${z}`;
        const state = grid3D.get(key) || ".";

        if (state === "#" && ![2, 3].includes(activeNeigbours)) {
          newGrid.set(key, ".");
        } else if (state === "." && activeNeigbours === 3) {
          newGrid.set(key, "#");
        } else {
          newGrid.set(key, state);
        }
      }
    }
  }

  grid3D = newGrid;
}

let grid4D = create4DGrid(input);

for (let i = 0; i < 6; i++) {
  const xs = [...grid4D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[0])
  );
  const minX = Math.min(...xs);
  const maxX = Math.max(...xs);

  const ys = [...grid4D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[1])
  );
  const minY = Math.min(...ys);
  const maxY = Math.max(...ys);

  const zs = [...grid4D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[2])
  );
  const minZ = Math.min(...zs);
  const maxZ = Math.max(...zs);

  const ws = [...grid4D.keys()].map((key) => key.split(",")).map((x) =>
    Number(x[3])
  );
  const minW = Math.min(...ws);
  const maxW = Math.max(...ws);

  const newGrid = new Map<string, string>();

  for (let x = minX - 1; x <= maxX + 1; x++) {
    for (let y = minY - 1; y <= maxY + 1; y++) {
      for (let z = minZ - 1; z <= maxZ + 1; z++) {
        for (let w = minW - 1; w <= maxW + 1; w++) {
          const neigbours = get4DNeighbors(x, y, z, w, grid4D);
          const activeNeigbours = neigbours.filter((x) => x === "#").length;
          const key = `${x},${y},${z},${w}`;
          const state = grid4D.get(key) || ".";

          if (state === "#" && ![2, 3].includes(activeNeigbours)) {
            newGrid.set(key, ".");
          } else if (state === "." && activeNeigbours === 3) {
            newGrid.set(key, "#");
          } else {
            newGrid.set(key, state);
          }
        }
      }
    }
  }

  grid4D = newGrid;
}

const part1 = [...grid3D.values()].filter((x) => x === "#").length;
const part2 = [...grid4D.values()].filter((x) => x === "#").length;

console.log(`Part 1: ${part1}`);
console.log(`Part 2: ${part2}`);
