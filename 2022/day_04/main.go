package main

import (
	"fmt"
	"io/ioutil"
	"strconv"
	"strings"
)

type assignment struct {
	start int
	end   int
}

func createAssignment(s string) assignment {
	parts := strings.Split(s, "-")
	start, _ := strconv.Atoi(parts[0])
	end, _ := strconv.Atoi(parts[1])

	return assignment{start: start, end: end}
}

func main() {
	content, _ := ioutil.ReadFile("./input.txt")
	pairs := strings.Split(string(content), "\n")
	contains := 0

	for _, assignments := range pairs {
		s := strings.Split(assignments, ",")
		assignment1, assignment2 := createAssignment(s[0]), createAssignment(s[1])

		if assignment1.start <= assignment2.start && assignment1.end >= assignment2.end {
			contains += 1

			continue
		}

		if assignment2.start <= assignment1.start && assignment2.end >= assignment1.end {
			contains += 1

			continue
		}
	}

	fmt.Printf("Part 1: %v\n", contains)

	overlap := 0

	for _, assignments := range pairs {
		s := strings.Split(assignments, ",")
		assignment1, assignment2 := createAssignment(s[0]), createAssignment(s[1])

		if assignment1.start >= assignment2.start && assignment1.start <= assignment2.end {
			overlap += 1

			continue
		}

		if assignment1.end >= assignment2.start && assignment1.end <= assignment2.end {
			overlap += 1

			continue
		}

		if assignment2.start >= assignment1.start && assignment2.start <= assignment1.end {
			overlap += 1

			continue
		}

		if assignment2.end >= assignment1.start && assignment2.end <= assignment1.end {
			overlap += 1

			continue
		}
	}

	fmt.Printf("Part 2: %v\n", overlap)
}
