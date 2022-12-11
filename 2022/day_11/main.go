package main

import (
	"fmt"
	"os"
	"sort"
	"strconv"
	"strings"
)

type monkey struct {
	items []int
	op    string
	test  func(int) int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	lcm := 1

	var monkeys []monkey

	for _, s := range strings.Split(string(content), "\n\n") {
		lines := strings.Split(s, "\n")

		var items []int
		si := lines[1]
		si = strings.Replace(si, "Starting items: ", "", 1)
		for _, v := range strings.Split(strings.TrimSpace(si), ", ") {
			n, _ := strconv.Atoi(v)
			items = append(items, n)
		}

		op := lines[2]
		op = strings.Replace(op, "Operation: new = ", "", 1)
		op = strings.TrimSpace(op)

		if op == "" {
			fmt.Println(lines[2])
			panic("a")
		}

		tl := strings.TrimSpace(lines[3]) + " " + strings.TrimSpace(lines[4]) + " " + strings.TrimSpace(lines[5])
		var div, t, f int

		_, _ = fmt.Sscanf(tl, "Test: divisible by %d If true: throw to monkey %d If false: throw to monkey %d", &div, &t, &f)

		m := monkey{items: items, op: op}

		m.test = func(wl int) int {
			if wl%div == 0 {
				return t
			} else {
				return f
			}
		}

		lcm *= div

		monkeys = append(monkeys, m)
	}

	fmt.Printf("Part 1: %v\n", playRounds(monkeys, 20, func(wl int) int { return wl / 3 }))
	fmt.Printf("Part 2: %v\n", playRounds(monkeys, 10000, func(wl int) int { return wl % lcm }))
}

func playRounds(monkeys []monkey, rounds int, manWorry func(int) int) int {
	monkeys = append([]monkey{}, monkeys...)
	inspections := make([]int, len(monkeys))

	for r := 0; r < rounds; r++ {
		for i := 0; i < len(monkeys); i++ {
			m := monkeys[i]

			for len(m.items) > 0 {
				var item int
				item, m.items = m.items[0], m.items[1:]

				wl := runOp(strings.Replace(m.op, "old", strconv.Itoa(item), -1))
				wl = manWorry(wl)
				monkeys[m.test(wl)].items = append(monkeys[m.test(wl)].items, wl)

				monkeys[i] = m

				inspections[i]++
			}
		}
	}

	sort.Ints(inspections)

	return inspections[len(inspections)-2] * inspections[len(inspections)-1]
}

func runOp(op string) int {
	var a, b int
	var o string

	_, _ = fmt.Sscanf(strings.TrimSpace(op), "%d %s %d", &a, &o, &b)

	switch o {
	case "+":
		return a + b
	case "*":
		return a * b
	default:
		panic("unknown op")
	}
}
