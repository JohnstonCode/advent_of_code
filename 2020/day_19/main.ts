const input: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n\n").filter((x) => x);

const rules: Record<string, string> = {};

for (const line of input[0].trim().split("\n")) {
  const [id, rule] = line.split(":");

  rules[id] = rule.trim();
}

const rulesRegex: Record<string, string> = {};

function getRule(rule: string, rules: Record<string, string>): string {
  if (rule in rulesRegex) {
    return rulesRegex[rule];
  }

  let result = "";

  if (/^"/.test(rule)) {
    result = rule.replace(/"/g, "");
  } else if (/\|/.test(rule)) {
    const parts = rule.split(" | ");

    result = `(${getRule(parts[0], rules)}|${getRule(parts[1], rules)})`;
  } else {
    result = rule.split(" ").map((r) => getRule(rules[r], rules)).join("");
  }

  rulesRegex[rule] = result;

  return result;
}

const zeroRegex = new RegExp(`^${getRule(rules[0], rules)}$`);
let part1 = 0;

for (const line of input[1].trim().split("\n")) {
  if (zeroRegex.test(line)) {
    part1++;
  }
}

console.log(`Part 1: ${part1}`);

rules[8] = "42 | 42 8";
rules[11] = "42 31 | 42 11 31";

getRule(rules[42], rules);
getRule(rules[31], rules);

const rule = new RegExp(
  `^(?<group42>(${rulesRegex[rules[42]]})+)(?<group31>(${
    rulesRegex[rules[31]]
  })+)$`,
);

let part2 = 0;

for (const line of input[1].trim().split("\n")) {
  const matches = rule.exec(line);

  if (matches) {
    const { groups } = matches;
    const matches42 =
      groups?.group42.match(new RegExp(rulesRegex[rules[42]], "g"))?.length ||
      0;
    const matches31 =
      groups?.group31.match(new RegExp(rulesRegex[rules[31]], "g"))?.length ||
      0;

    if (matches42 > matches31) {
      part2++;
    }
  }
}
console.log(`Part 2: ${part2}`);
