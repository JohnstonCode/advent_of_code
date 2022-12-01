package main

import (
	"fmt"
	"io/ioutil"
	"sort"
	"strconv"
	"strings"
)

func main() {
	content, _ := ioutil.ReadFile("./input.txt")
	parts := strings.Split(string(content), "\n\n")

	totals := make([]int, len(parts))

	for i, v := range parts {
		p := strings.Split(v, "\n")
		total := 0

		for _, s := range p {
			num, _ := strconv.Atoi(s)

			total += num
		}

		totals[i] = total
	}

	sort.Ints(totals)

	fmt.Printf("Part 1: %v\n", totals[len(totals)-1])

	part2 := 0

	for _, v := range totals[len(totals)-3:] {
		part2 += v
	}

	fmt.Printf("Part 1: %v\n", part2)
}
