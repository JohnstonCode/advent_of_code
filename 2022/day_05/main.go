package main

import (
	"fmt"
	"io/ioutil"
	"strconv"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("./input.txt")
	s := strings.Split(string(content), "\n\n")
	m := make(map[int]string)
	stacks := s[0]
	stackLength := (len([]rune(strings.Split(string(stacks), "\n")[0])) / 4) + 1

	for _, line := range strings.Split(string(stacks), "\n") {
		for i := 1; i < len(line); i += 2 {
			if strings.TrimSpace(string(line[i])) != "" {
				key := (i / 4) + 1
				m[key] = string(line[i]) + m[key]
			}
		}
	}

	moves := s[1]
	part1Map := make(map[int]string)

	for k, v := range m {
		part1Map[k] = v
	}

	for _, move := range strings.Split(moves, "\n") {
		s := strings.Split(move, " ")
		amount, _ := strconv.Atoi(s[1])
		from, _ := strconv.Atoi(s[3])
		to, _ := strconv.Atoi(s[5])

		a := part1Map[from]
		t := a[len(a)-amount:]
		t = Reverse(t)
		part1Map[from] = a[:len(a)-amount]

		part1Map[to] += t
	}

	part1 := ""

	for i := 1; i < stackLength+1; i++ {
		a := part1Map[i]

		part1 += a[len(a)-1:]
	}

	fmt.Printf("Part 1: %v\n", part1)

	part2Map := make(map[int]string)

	for k, v := range m {
		part2Map[k] = v
	}

	for _, move := range strings.Split(moves, "\n") {
		s := strings.Split(move, " ")
		amount, _ := strconv.Atoi(s[1])
		from, _ := strconv.Atoi(s[3])
		to, _ := strconv.Atoi(s[5])

		a := part2Map[from]
		t := a[len(a)-amount:]
		part2Map[from] = a[:len(a)-amount]

		part2Map[to] += t
	}

	part2 := ""

	for i := 1; i < stackLength+1; i++ {
		a := part2Map[i]

		part2 += a[len(a)-1:]
	}

	fmt.Printf("Part 1: %v\n", part2)
}

func Reverse(s string) string {
	runes := []rune(s)
	for i, j := 0, len(runes)-1; i < j; i, j = i+1, j-1 {
		runes[i], runes[j] = runes[j], runes[i]
	}
	return string(runes)
}
