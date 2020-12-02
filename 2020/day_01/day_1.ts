const inputs =
  (await Deno.readTextFile(
    new URL(".", import.meta.url).pathname + "/input.txt",
  )).split("\n").map(Number);

for (let i = 0; i < inputs.length - 1; i++) {
  for (let j = i + 1; j < inputs.length; j++) {
    if ((inputs[i] + inputs[j]) === 2020) {
      console.log(inputs[i] * inputs[j]);
    }
  }
}

for (let i = 0; i < inputs.length - 2; i++) {
  for (let j = i + 1; j < inputs.length - 1; j++) {
    for (let k = j + 1; k < inputs.length; k++) {
      if ((inputs[i] + inputs[j] + inputs[k]) === 2020) {
        console.log(inputs[i] * inputs[j] * inputs[k]);
      }
    }
  }
}
