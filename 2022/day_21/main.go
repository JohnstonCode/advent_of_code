package main

import (
	"fmt"
	"os"
	"regexp"
	"strings"
)

type monkey struct {
	num  int
	op   string
	mon1 string
	mon2 string
}

var monkeys = make(map[string]monkey)

func main() {
	content, _ := os.ReadFile("./input.txt")
	monkeys = getMonkeys(string(content))

	fmt.Println(getNumber("root"))

	root := monkeys["root"]
	root.op = "="
	monkeys["root"] = root

	high := 20000000000000
	low := 0

	for {
		mid := (high + low) / 2
		humn := monkeys["humn"]
		humn.num = mid
		monkeys["humn"] = humn
		num := getNumber("root")

		if num == 0 {
			fmt.Println(mid)
			break
		} else if num == 1 {
			low = mid
		} else {
			high = mid
		}
	}
}

func getMonkeys(input string) map[string]monkey {
	ret := make(map[string]monkey)

	for _, line := range strings.Split(input, "\n") {
		line = strings.Replace(line, ":", "", 1)
		var name string

		re := regexp.MustCompile(`^\w+\s\d+`)
		if re.MatchString(line) {
			m := monkey{}
			_, _ = fmt.Sscanf(strings.TrimSpace(line), "%s %d", &name, &m.num)
			ret[name] = m

			continue
		}

		m := monkey{}
		_, _ = fmt.Sscanf(line, "%s %s %s %s", &name, &m.mon1, &m.op, &m.mon2)
		ret[name] = m
	}

	return ret
}

func getNumber(m string) int {
	mm := monkeys[m]

	if mm.num != 0 {
		return mm.num
	}

	return runOp(mm.op, getNumber(mm.mon1), getNumber(mm.mon2))
}

func runOp(op string, a int, b int) int {
	switch op {
	case "+":
		return a + b
	case "/":
		return a / b
	case "*":
		return a * b
	case "-":
		return a - b
	case "=":
		if a > b {
			return 1
		} else if a < b {
			return -1
		} else {
			return 0
		}
	default:
		panic("unknown op")
	}
}
