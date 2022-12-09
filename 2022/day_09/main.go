package main

import (
	"fmt"
	"image"
	"os"
	"strconv"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")
	head, tail := image.Point{}, image.Point{}
	tailVisits := map[image.Point]bool{}
	rope := make([]image.Point, 10)
	tailVisitsP2 := map[image.Point]bool{}

	for _, line := range strings.Split(string(content), "\n") {
		p := strings.Split(strings.TrimSpace(line), " ")
		dir := p[0]
		moves, _ := strconv.Atoi(p[1])

		for i := 0; i < moves; i++ {
			head = movePoint(head, dir)
			rope[0] = movePoint(rope[0], dir)

			d := head.Sub(tail)
			if abs(d.X) > 1 || abs(d.Y) > 1 {
				tail = tail.Add(image.Point{moveKnot(d.X), moveKnot(d.Y)})
			}

			for i := 1; i < len(rope); i++ {
				if d := rope[i-1].Sub(rope[i]); abs(d.X) > 1 || abs(d.Y) > 1 {
					rope[i] = rope[i].Add(image.Point{moveKnot(d.X), moveKnot(d.Y)})
				}
			}

			tailVisitsP2[rope[len(rope)-1]] = true
			tailVisits[tail] = true
		}
	}

	fmt.Printf("Part 1: %v\n", len(tailVisits))
	fmt.Printf("Part 2: %v\n", len(tailVisitsP2))
}

func movePoint(point image.Point, dir string) image.Point {
	switch dir {
	case "U":
		return point.Add(image.Point{X: 0, Y: 1})
	case "R":
		return point.Add(image.Point{X: 1, Y: 0})
	case "D":
		return point.Add(image.Point{X: 0, Y: -1})
	case "L":
		return point.Add(image.Point{X: -1, Y: 0})
	default:
		panic("NOOOO")
	}
}

func abs(x int) int {
	if x < 0 {
		return -x
	}
	return x
}

func moveKnot(x int) int {
	if x < 0 {
		return -1
	} else if x > 0 {
		return 1
	}

	return 0
}
