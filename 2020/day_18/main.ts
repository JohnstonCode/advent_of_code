const expressions: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x);

function solve(expression: string): string {
  let tokens = expression.split(" ");

  while (tokens.length > 1) {
    tokens = [eval(tokens.slice(0, 3).join(""))].concat(tokens.slice(3));
  }

  return tokens[0];
}

function solveMultiplicationFirst(expression: string): string {
  while (/\+/.test(expression)) {
    expression = expression.replace(/(\d+) \+ (\d+)/g, (_, num1, num2) => {
      return String(Number(num1) + Number(num2));
    });
  }

  return eval(expression);
}

function solveWithParenthesis(
  expression: string,
  solver: (expression: string) => string,
): number {
  while (/\(/.test(expression)) {
    expression = expression.replace(/\(([^()]+)\)/g, (match, group) => {
      return solver(group);
    });
  }

  return parseInt(solver(expression));
}

const part1 = expressions.reduce(
  (sum, expression) => sum += solveWithParenthesis(expression, solve),
  0,
);
const part2 = expressions.reduce(
  (sum, expression) =>
    sum += solveWithParenthesis(expression, solveMultiplicationFirst),
  0,
);

console.log(`Part 1: ${part1}`);
console.log(`Part 2: ${part2}`);
